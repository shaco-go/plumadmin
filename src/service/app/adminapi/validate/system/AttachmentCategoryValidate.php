<?php

namespace app\adminapi\validate\system;

use think\Validate;

class AttachmentCategoryValidate extends Validate
{
    protected $rule = [
        'id'        => 'require',
        'name'      => 'require',
        'parent_id' => 'require',
    ];

    protected $message = [
        'name.require'      => '请输入分类名称',
        'parent_id.require' => '父级ID不能为空',
    ];

    protected $scene = [
        'create' => ['name', 'parent_id'],
        'update' => ['id', 'name', 'parent_id'],
    ];
}