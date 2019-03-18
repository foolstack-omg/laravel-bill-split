<?php
/**
 * Created by PhpStorm.
 * User: liuxiaofeng
 * Date: 2019-03-16
 * Time: 21:21
 */

namespace App\Policies;


use App\Models\Bill;
use App\Models\User;

class BillPolicy extends Policy
{
    public function update(User $user, Bill $bill) {
        return $user->isAuthorOf($bill);
    }
}
