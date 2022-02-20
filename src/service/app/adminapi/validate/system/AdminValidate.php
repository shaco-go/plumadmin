<?php

namespace app\adminapi\validate\system;

use plum\utils\Token;
use think\Validate;

class AdminValidate extends Validate
{
    protected $rule = [
        'id'               => 'require',
        'username'         => 'require',
        'password'         => 'require',
        'nickname'         => 'require',
        'avatar'           => 'require',
        'is_super'         => 'boolean',
        'role_ids'         => 'require',
        'refresh_token'    => 'require',
        'old_password'     => 'require',
        'new_password'     => 'require|different:old_password',
        'confirm_password' => 'require|confirm:new_password',
    ];

    protected $message = [
        'username.require'         => '请输入用户名',
        'password.require'         => '请输入密码',
        'nickname.require'         => '请输入昵称',
        'avatar.require'           => '请上传图片',
        'role_ids.require'         => '请选择角色',
        'new_password.different'   => '新密码不能与旧密码一致',
        'confirm_password.confirm' => '确认密码与新密码不一致'
    ];

    protected $scene = [
        'create'      => ['username', 'password', 'nickname', 'avatar', 'role_ids', 'is_super'],
        'update'      => ['id', 'username', 'nickname', 'avatar', 'role_ids', 'is_super'],
        'loginNormal' => ['username', 'password'],
        'refresh'     => ['refresh_token'],
        'password'    => ['new_password', 'old_password', 'confirm_password']
    ];
}