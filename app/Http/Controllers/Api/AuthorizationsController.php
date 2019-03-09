<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\WeappAuthorizationRequest;
use Auth;
use App\Models\User;
use Illuminate\Http\Request;

class AuthorizationsController extends Controller
{
    public function test(){
        return $this->response()->noContent();
    }

//    public function weappStore(WeappAuthorizationRequest $request)
//    {
//        $code = $request->code;
//
//        // 根据 code 获取微信 openid 和 session_key
//        $miniProgram = \EasyWeChat::miniProgram();
//        $data = $miniProgram->auth->session($code);
//
//        // 如果结果错误，说明 code 已过期或不正确，返回 401 错误
//        if (isset($data['errcode'])) {
//            return $this->response->errorUnauthorized('code 不正确');
//        }
//
//        // 找到 openid 对应的用户
//        $user = User::where('weapp_openid', $data['openid'])->first();
//
//        $attributes['weixin_session_key'] = $data['session_key'];
//
//        // 未找到对应用户则需要提交用户名密码进行用户绑定
//        if (!$user) {
//            // 如果未提交用户名密码，403 错误提示
//            if (!$request->username) {
//                return $this->response->errorForbidden('用户不存在');
//            }
//
//            $username = $request->username;
//
//            // 用户名可以是邮箱或电话
//            filter_var($username, FILTER_VALIDATE_EMAIL) ?
//                $credentials['email'] = $username :
//                $credentials['phone'] = $username;
//
//            $credentials['password'] = $request->password;
//
//            // 验证用户名和密码是否正确
//            if (!Auth::guard('api')->once($credentials)) {
//                return $this->response->errorUnauthorized('用户名或密码错误');
//            }
//
//            // 获取对应的用户
//            $user = Auth::guard('api')->getUser();
//            $attributes['weapp_openid'] = $data['openid'];
//        }
//
//        // 更新用户数据
//        $user->update($attributes);
//
//        // 为对应用户创建 JWT
//        $token = Auth::guard('api')->fromUser($user);
//
//        return $this->respondWithToken($token)->setStatusCode(201);
//    }

    public function update(Request $request)
    {
        $token = Auth::guard('api')->refresh();
        return $this->respondWithToken($token);
    }

    public function destroy()
    {
        Auth::guard('api')->logout();
        return $this->response->noContent();
    }

    protected function respondWithToken($token)
    {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }
}
