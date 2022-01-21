<?php

namespace app\adminapi\validate\system;

use think\Validate;

class MenusValidate extends Validate
{
    protected $rule = [
        'id'          => 'require',
        'title'       => 'require',
        'name'        => 'requireIn:type,2',
        'mark'        => 'requireIn:type,3',
        'type'        => 'require',
        'method'      => 'requireIn:type,3',
        'routes'      => 'requireIn:type,2',
        'component'   => 'requireIn:type,2',
        'menu_hidden' => 'boolean',
        'keep_alive'  => 'boolean',
        'sort'        => 'integer'
    ];

    protected $message = [
        'title.require'       => '请输入菜单名称',
        'name.requireIn'      => '请输入组件name值',
        'mark.requireIn'      => '请输入前端标识',
        'type.require'        => '请选择类型',
        'method.requireIn'    => '请选择权限',
        'routes.requireIn'    => '请输入路由',
        'component.requireIn' => '请选择组件',
        'sort.integer'        => '请正确输入排序',
    ];

    protected $scene = [
        'create' => ['title', 'name', 'mark', 'type', 'method', 'routes', 'component', 'sort', 'menu_hidden', 'keep_alive'],
        'update' => ['id', 'title', 'name', 'mark', 'type', 'method', 'routes', 'component', 'sort', 'menu_hidden', 'keep_alive']
    ];
}