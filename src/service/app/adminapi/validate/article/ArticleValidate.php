<?php

namespace app\adminapi\validate\article;

use think\Validate;

class ArticleValidate extends Validate
{
    protected $rule = [
        'id'          => 'require',
        'category_id' => 'require',
        'title'       => 'require',
        'cover'       => 'require',
        'status'      => 'require'
    ];

    protected $message = [
        'category_id.require' => "请选择分类",
        'title.require'       => "请输入标题",
        'cover.require'       => "请上传封面图",
        'status.require'      => "请选择文章状态",
    ];

    protected $scene = [
        'create' => ['category_id', 'title', 'cover', 'status'],
        'update' => ['id', 'category_id', 'title', 'cover', 'status'],
    ];
}