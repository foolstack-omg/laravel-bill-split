<?php
/**
 * Created by PhpStorm.
 * User: liuxiaofeng
 * Date: 2019-03-16
 * Time: 21:28
 */

namespace App\Http\Controllers\Api;


use App\Http\Requests\Api\Bills\SaveRequest;
use App\Models\Activity;
use App\Models\ActivityParticipant;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\BillParticipant;
use DB;

class BillsController extends Controller
{
    public function save(SaveRequest $request, Activity $activity, Bill $bill = null) {
        $participants_collection = collect($request->participants);

        if(bccomp($request->money, $participants_collection->sum('split_money'), 2) !== 0){
            return $this->response->errorBadRequest();
        }

        if($participants_collection->pluck('user_id')->diff($activity->participants()->pluck('user_id'))->isNotEmpty()){
            return $this->response->errorBadRequest();
        }

        DB::transaction(function() use ($request, $activity, $bill, $participants_collection) {
            if(empty($bill)){
                $bill = new Bill();
                $bill->activity_id = $activity->id;
                $bill->user_id = $this->user()->id;
            }else{
                $this->authorize('update', $bill);
            }

            $bill->title = $request->title;
            $bill->description = $request->description ?? '';
            $bill->money = $request->money;
            $bill->save();

            $bill_items_collection = collect($request->bill_items);
            $bill_item_ids = $bill_items_collection->pluck('id')->filter(function($v) {
                return $v !== null;
            });

            BillItem::query()->where('bill_id', $bill->id)
                ->whereNotIn('id', $bill_item_ids)
                ->delete();

            $bill_items_collection->each(function($v) use ($bill){
                $v['bill_id'] = $bill->id;
                BillItem::query()->updateOrCreate(['id' => $v['id'] ?? null], $v);
            });

            BillParticipant::query()->where('bill_id', $bill->id)
                ->whereNotIn('user_id', $participants_collection->pluck('user_id')->filter(function($v) {
                    return $v !== null;
                }))
                ->delete();

            $fixed_participants = $participants_collection->filter(function($v) {
                return $v['fixed'];
            });
            $not_fixed_participants = $participants_collection->filter(function($v) {
                return !$v['fixed'];
            });

            $fixed_money = $fixed_participants->sum('split_money');

            $remain_money = bcsub($bill->money, $fixed_money, 2);

            $split_money = bcdiv($remain_money, $not_fixed_participants->count(), 2);

            $first_one_split_money = bcadd($split_money, bcsub($remain_money, bcmul($split_money, $not_fixed_participants->count(), 2), 2), 2);
            $first_one_split = false;
            $participants_collection->each(function($v, $k) use ($bill, $split_money, &$first_one_split, $first_one_split_money){
                $v['bill_id'] = $bill->id;
                if(!$v['fixed']) {
                    if(!$first_one_split){
                        $v['split_money'] = $first_one_split_money;
                        $first_one_split = true;
                    }else{
                        $v['split_money'] = $split_money;
                    }

                }

                BillParticipant::query()->updateOrCreate(
                    ['user_id' => $v['user_id'], 'bill_id' => $bill->id],
                    $v
                );
            });


        });

        return $this->response->accepted();

    }
}
