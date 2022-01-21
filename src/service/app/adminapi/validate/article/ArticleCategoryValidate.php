<?php

namespace app\adminapi\validate\article;

use think\Validate;

class ArticleCategoryValidate extends Validate
{
    protected $rule = [
        'id'   => 'require',
        'name' => 'require',
        'sort' => 'integer'
    ];

    protected $message = [
        'name.require' => '请输入分类名称'
    ];

    protected $scene = [
        'create' => ['name', 'sort'],
        'update' => ['id', 'name', 'sort'],
    ];
}