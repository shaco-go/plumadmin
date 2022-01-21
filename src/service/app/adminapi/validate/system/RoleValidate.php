<?php

namespace app\adminapi\validate\system;

use think\Validate;

class RoleValidate extends Validate
{
    protected $rule = [
        'id'       => 'require',
        'name'     => 'require',
        'rule_ids' => 'require|array',
        'remark'   => 'max:2000'
    ];

    protected $message = [
        'name.require'     => '请输入角色名称',
        'rule_ids.require' => '请选择权限',
        'rule_ids.array'   => '请选择正确的权限',
        'remark.max'       => '备注最大不可超过2000'
    ];

    protected $scene = [
        'create' => ['name', 'rule_ids', 'remark'],
        'update' => ['id', 'name', 'rule_ids', 'remark'],
    ];

}