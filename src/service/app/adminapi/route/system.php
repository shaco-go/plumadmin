<?php

use think\facade\Route;


Route::group('system', function () {
    //管理员
    Route::group('admin', function () {
        //分页
        Route::post('page', 'system.Admin/page');
        //新增
        Route::post('create', 'system.Admin/create');
        //修改
        Route::post('update', 'system.Admin/update');
        //详情
        Route::post('detail', 'system.Admin/detail');
        //删除
        Route::post('delete', 'system.Admin/delete');
    });

    //附件
    Route::group('attachment', function () {
        //附件分页
        Route::post('page', 'system.Attachment/page');
        //上传附近
        Route::post('upload', 'system.Attachment/upload');
        //移动附件
        Route::post('move', 'system.Attachment/move');
        //删除附件
        Route::post('delete', 'system.Attachment/delete');
    });

    //附件分类
    Route::group('attachment/category', function () {
        //树状
        Route::post('tree', 'system.AttachmentCategory/tree');
        //创建
        Route::post('create', 'system.AttachmentCategory/create');
        //修改
        Route::post('update', 'system.AttachmentCategory/update');
        //删除
        Route::post('delete', 'system.AttachmentCategory/delete');
    });

    //菜单
    Route::group('menus', function () {
        // 树状
        Route::post('tree', 'system.Menus/tree');
        // 新增
        Route::post('create', 'system.Menus/create');
        // 修改
        Route::post('update', 'system.Menus/update');
        // 详情
        Route::post('detail', 'system.Menus/detail');
        // 删除
        Route::post('delete', 'system.Menus/delete');

    });

    //角色
    Route::group('role', function () {
        // 分页
        Route::post('page', 'system.Role/page');
        // 列表
        Route::post('list', 'system.Role/list');
        // 新增
        Route::post('create', 'system.Role/create');
        // 修改
        Route::post('update', 'system.Role/update');
        // 详情
        Route::post('detail', 'system.Role/detail');
        // 删除
        Route::post('delete', 'system.Role/delete');

    });

    //设置
    Route::group('config',function(){
        //附件设置
        Route::post('attachment','system.Config/setAttachment');
        //获取附件设置
        Route::get('attachment','system.Config/getAttachment');
        //附件验证设置
        Route::post('attachment/validate','system.Config/setAttachmentValidate');
        //获取附件验证设置
        Route::get('attachment/validate','system.Config/getAttachmentValidate');
    });

    // 邮件
    Route::group('email',function(){
        // 测试邮件
        Route::post('test/send','system.Email/testSend');
        // 配置邮箱
        Route::post('config','system.Email/setConfig');
        // 获取邮箱
        Route::get('config','system.Email/getConfig');
    });

    // DEBUG
    Route::group('debug',function(){
        // 分页
        Route::post('page','system.Debug/page');
        // 删除
        Route::post('delete','system.Debug/delete');
        // 清除全部
        Route::post('clear','system.Debug/clear');
    });

    // 操作日志
    Route::group('operation/log',function(){
        // 分页
        Route::post('page','system.OperationLog/page');
        // 删除
        Route::post('delete','system.OperationLog/delete');
        // 清除
        Route::post('clear','system.OperationLog/clear');
    });

})->middleware([
    \app\adminapi\middleware\AuthMiddleware::class,
    \app\adminapi\middleware\PermissionMiddleware::class,
    \app\adminapi\middleware\OperationLogMiddleware::class
]);

