<?php
/**
 * Created by PhpStorm.
 * User: liuxiaofeng
 * Date: 2019-03-16
 * Time: 16:50
 */

namespace App\Http\Controllers\Api;


use App\Handlers\ImageUploadHandler;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function me()
    {
        return $this->response->item($this->user(), new UserTransformer());
    }

    public function storeImages(Request $request, ImageUploadHandler $uploader)
    {
        $types = ['wxpay', 'alipay'];

        if(!in_array($request->type, $types)) {
            return $this->response->error('不支持的支付类型', 500);
        }

        $user = $this->user();

        $size = 350;
        $result = $uploader->save($request->image, str_plural($request->type), $user->id, $size);

        switch ($request->type) {
            case 'wxpay':
                $user->wxpay = $result['path'];
                break;
            case 'alipay':
                $user->alipay = $result['path'];
                break;
            default:
                return $this->response->error('不支持的支付类型', 500);
        }
        $user->save();

        return $this->response->item($user, new UserTransformer())->setStatusCode(201);
    }

    public function deleteImages(Request $request) {
        $user = $this->user();
        $types = ['wxpay', 'alipay'];

        if(!in_array($request->type, $types)) {
            return $this->response->error('不支持的支付类型', 500);
        }
        switch ($request->type) {
            case 'wxpay':
                $user->wxpay = '';
                break;
            case 'alipay':
                $user->alipay = '';
                break;
            default:
                return $this->response->error('不支持的支付类型', 500);
        }

        $user->save();

        return $this->response->item($user, new UserTransformer())->setStatusCode(201);


    }
}
