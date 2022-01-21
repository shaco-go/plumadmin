<?php

namespace app\model\system;

use app\model\pivot\SystemRoleMenusModel;
use plum\basic\BaseModel;
use think\model\concern\SoftDelete;

class SystemRoleModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'system_role';

    /**
     * 关联表 - rule
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/28
     */
    public function rule()
    {
        return $this->belongsToMany(SystemMenusModel::class, SystemRoleMenusModel::class, 'menus_id', 'role_id');
    }
}