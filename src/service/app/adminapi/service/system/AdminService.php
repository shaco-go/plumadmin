<?php

namespace app\adminapi\service\system;

use app\model\pivot\SystemAdminRoleModel;
use app\model\system\SystemAdminModel;
use plum\exception\FailException;
use think\facade\Db;
use function request;

class AdminService
{
    /**
     * 创建管理员
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public static function create($data)
    {
        //只有管理员才可以创建管理员
        if (!request()->middleware('userinfo')['is_super']) {
            unset($data['is_super']);
        }
        self::isExistsUsername($data['username']);
        Db::startTrans();
        try {
            //创建管理员
            $admin = SystemAdminModel::create($data);
            //关联角色
            $admin->role()->saveAll($data['role_ids']);
            Db::commit();
        } catch (\Throwable $e) {
            Db::rollback();
            throw new FailException('操作失败');
        }
    }

    /**
     * 编辑管理员
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public static function update($data)
    {
        //只有管理员才可以创建管理员
        if (!request()->middleware('userinfo')['is_super']) {
            unset($data['is_super']);
        }
        //空密码进行删除
        if (empty($data['password'])) {
            unset($data['password']);
        }
        self::isExistsUsername($data['username'], $data['id']);
        Db::startTrans();
        try {
            //修改管理员
            $admin = SystemAdminModel::update($data);
            //删除角色
            SystemAdminRoleModel::where('admin_id', $admin['id'])->delete();
            //关联角色
            $admin->role()->saveAll($data['role_ids']);
            Db::commit();
        } catch (\Throwable $e) {
            Db::rollback();
            throw new FailException('操作失败');
        }
    }

    /**
     * 校验用户名是否存在
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/10
     */
    public static function isExistsUsername($username, $id = null)
    {
        $exists = SystemAdminModel::where('username', $username);
        //排除id情况
        if ($id) {
            $exists->where('id', '<>', $id);
        }
        if ($exists->find()) {
            throw new FailException('用户名已存在');
        }
    }

    /**
     * 修改当前用户的密码
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/13
     */
    public static function updateCurrentPassword($password)
    {
        $user = request()->middleware('userinfo');
        $user->password = password_hash($password,PASSWORD_DEFAULT);
        $user->save();
    }
}