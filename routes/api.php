<?php

use Illuminate\Http\Request;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['serializer:array', 'bindings', 'change-locale']
], function($api) {

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function($api) {
        // 小程序登录
        $api->post('weapp/authorizations', 'AuthorizationsController@weappStore')
            ->name('api.weapp.authorizations.store');

        // 刷新token
        $api->put('authorizations/current', 'AuthorizationsController@update')
            ->name('api.authorizations.update');
        // 删除token
        $api->delete('authorizations/current', 'AuthorizationsController@destroy')
            ->name('api.authorizations.destroy');

    });

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        // 游客可以访问的接口
        $api->get('qas', 'QAsController@index')
            ->name('api.qas.index');


        // 需要 token 验证的接口
        $api->group(['middleware' => 'api.auth'], function($api) {

            // 当前登录用户信息
            $api->get('user', 'UsersController@me')
                ->name('api.user.me');

            $api->get('/activities', "ActivitiesController@myActivities")
                ->name('api.activities');

            $api->post('activities/save/{activity?}', "ActivitiesController@save")
                ->name('api.activities.save');

            $api->get('activities/{activity}', 'ActivitiesController@show')
                ->name('api.activities.show');

            $api->put('activities/{activity}/participate', 'ActivitiesController@participate')
                ->name('api.activities.participate');

            $api->delete('activities/{activity}', 'ActivitiesController@quit')
                ->name('api.activities.quit');

            $api->get('activities/{activity}/participants', 'ActivitiesController@participants')
                ->name('api.activities.participants');

            $api->delete('activities/{activity}/remove_participants', 'ActivitiesController@removeParticipants')
                ->name('api.activities.participants.delete');

            $api->get('activities/{activity}/bills', 'BillsController@bills')
                ->name('api.activities.bills.index');

            $api->post('activities/{activity}/bills/{bill?}', 'BillsController@save')
                ->name('api.activities.bills.save');

            $api->get('bills/{bill}', 'BillsController@show')
                ->name('api.bills.show');

            $api->delete('bills/{bill}', 'BillsController@delete')
                ->name('api.bills.delete');

            $api->get('bills/{bill}/poster', 'BillsController@poster')
                ->name('api.bills.poster');


        });
    });






});
