<?php

use think\facade\Route;

//管理员账号登录
Route::post('login/account', 'Login/account')
    ->middleware(\app\adminapi\middleware\OperationLogMiddleware::class);

//刷新token
Route::get('refresh', 'Login/refresh')
    ->middleware(\app\adminapi\middleware\OperationLogMiddleware::class);

Route::group(function () {
    //管理员登出
    Route::get('logout', 'Login/logout');
    //刷新token
    Route::get('refresh', 'Login/refresh');
    //管理员信息
    Route::get('userinfo', 'system.Userinfo/detail');
    //编辑管理员信息
    Route::post('userinfo/update', 'system.Userinfo/patch');
    //管理员修改密码
    Route::post('userinfo/password/update', 'system.Userinfo/updatePassword');
    //获取当前应用的所有路由
    Route::get('common/routes', 'Common/routes');
})->middleware([
    \app\adminapi\middleware\AuthMiddleware::class,
    \app\adminapi\middleware\PermissionMiddleware::class,
    \app\adminapi\middleware\OperationLogMiddleware::class
]);