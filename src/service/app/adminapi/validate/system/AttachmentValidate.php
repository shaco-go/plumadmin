<?php

namespace app\adminapi\validate\system;

use think\Validate;

class AttachmentValidate extends Validate
{
    protected $rule = [
        'ids'         => 'require|array',
        'category_id' => 'require|integer'
    ];

    protected $scene = [
        'move' => ['ids', 'category_id']
    ];
}