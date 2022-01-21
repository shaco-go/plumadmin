<?php

use think\facade\Route;

// 文章
Route::group('article', function () {
    // 分页
    Route::post('page', 'article.Article/page');
    // 新增
    Route::post('create', 'article.Article/create');
    // 修改
    Route::post('update', 'article.Article/update');
    // 详情
    Route::post('detail', 'article.Article/detail');
    // 删除
    Route::post('delete', 'article.Article/delete');

    // 文章分类
    Route::group('category', function () {
        // 分页
        Route::post('page', 'article.ArticleCategory/page');
        // 列表
        Route::post('list', 'article.ArticleCategory/list');
        // 新增
        Route::post('create', 'article.ArticleCategory/create');
        // 修改
        Route::post('update', 'article.ArticleCategory/update');
        // 详情
        Route::post('detail', 'article.ArticleCategory/detail');
        // 删除
        Route::post('delete', 'article.ArticleCategory/delete');
    });
})->middleware([
    \app\adminapi\middleware\AuthMiddleware::class,
    \app\adminapi\middleware\PermissionMiddleware::class
]);