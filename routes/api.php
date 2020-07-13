<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->namespace('Api')->name('api.v1.')
    ->middleware('throttle:' . config('api.rate_limits.sign'))->group(function () {
//    Route::get('version', function () {
//        abort(403, 'test');
//        return 'this is version v1';
//    })->name('version');
        // 图片验证码
        Route::post('captchas', 'CaptchasController@store')
            ->name('captchas.store');
        // 短信验证码
        Route::post('verificationCodes', 'VerificationCodesController@store')
            ->name('verificationCodes.store');
        // 用户注册
        Route::post('users', 'UsersController@store')
            ->name('users.store');
        // 第三方登录
        Route::post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
            ->where('social_type', 'weixin')
            ->name('socials.authorizations.store');
        // 登录
        Route::post('authorizations', 'AuthorizationsController@store')
            ->name('api.authorizations.store');

        // 刷新token
        Route::put('authorizations/current', 'AuthorizationsController@update')
            ->name('authorizations.update');
        // 删除token
        Route::delete('authorizations/current', 'AuthorizationsController@destroy')
            ->name('authorizations.destroy');
    });

Route::middleware('throttle:' . config('api.rate_limits.access'))
    ->group(function () {

    });
//https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1bfe22f0c3c83256&redirect_uri=http://larabbs.test&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect
//https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx1bfe22f0c3c83256&secret=5bc4cf1ed9e7664e7a34aa2f11d4a805&code=061pNsqx1Ieuwc0Yxiox1NVpqx1pNsqM&grant_type=authorization_code
