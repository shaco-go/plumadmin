<?php

namespace app\adminapi\controller;

use app\adminapi\Controller;
use app\adminapi\validate\system\AdminValidate;
use app\model\system\SystemAdminModel;
use plum\exception\FailException;
use plum\utils\Token;

class Login extends Controller
{
    /**
     * 账号登录
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function account()
    {
        $data = $this->request->param();
        validate(AdminValidate::class)
            ->scene('loginNormal')
            ->check($data);
        $admin = SystemAdminModel::where('username', $data['username'])->find();
        if (!$admin || !password_verify($data['password'], $admin['password']))
            throw new FailException('账号或密码不正确');
        $token = Token::build($admin['id'], app('http')->getName());
        trace("{$data['username']} ,用户登录", 'op');
        return $this->success($token);
    }

    /**
     * 登出
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function logout()
    {
        $username = $this->userinfo['username'] ?? '';
        Token::invalid(app('http')->getName());
        trace("$username ,用户登出",'op');
        return $this->success('登出成功');
    }

    /**
     * 刷新
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/1
     */
    public function refresh()
    {
        validate(AdminValidate::class)
            ->scene('refresh')
            ->check($this->request->param());
        $token = Token::refresh($this->request->param('refresh_token'), app('http')->getName());
        return $this->success($token);
    }
}