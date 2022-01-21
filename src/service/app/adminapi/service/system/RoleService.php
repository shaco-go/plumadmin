<?php

namespace app\adminapi\service\system;

use app\model\pivot\SystemRoleMenusModel;
use app\model\system\SystemRoleModel;
use plum\exception\FailException;
use think\facade\Db;

class RoleService
{
    /**
     * 新增角色
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public static function create($data)
    {
        Db::startTrans();
        try{
            //创建角色
            $role = SystemRoleModel::create($data);
            //添加关联数据
            $role->rule()->saveAll($data['rule_ids']);
            Db::commit();
        }catch(\Throwable $e){
            Db::rollback();
            throw new FailException('操作失败');
        }
    }

    /**
     * 更新角色
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public static function update($data)
    {
        Db::startTrans();
        try{
            //修改角色
            $role = SystemRoleModel::update($data);
            //删除关联数据
            SystemRoleMenusModel::where('role_id',$role['id'])->delete();
            //添加关联数据
            $role->rule()->saveAll($data['rule_ids']);
            Db::commit();
        }catch(\Throwable $e){
            Db::rollback();
            throw new FailException('操作失败');
        }
    }
}