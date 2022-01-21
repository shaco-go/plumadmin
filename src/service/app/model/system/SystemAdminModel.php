<?php

namespace app\model\system;

use app\model\pivot\SystemAdminRoleModel;
use plum\basic\BaseModel;
use plum\exception\FailException;
use think\model\concern\SoftDelete;

class SystemAdminModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'system_admin';
    protected $type = ['is_super' => 'boolean'];

    /**
     * 关联表 - role
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/28
     */
    public function role()
    {
        return $this->belongsToMany(SystemRoleModel::class, SystemAdminRoleModel::class, 'role_id', 'admin_id');
    }

    /**
     * 设置器 - password
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function setPasswordAttr($value)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    /**
     * 搜索器 - username
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function searchUsernameAttr($query, $value)
    {
        $query->whereLike('username', "%{$value}%");
    }

    /**
     * 搜索器 - nickname
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function searchNicknameAttr($query, $value)
    {
        $query->whereLike('nickname', "%{$value}%");
    }

    /**
     * 搜索器 - is_super
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function searchIsSuperAttr($query, $value)
    {
        $query->where('is_super', $value ? 1 : 0);
    }


    /**
     * 获取所有的规则(目录,菜单,权限)
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/28
     */
    public function getAllRule()
    {
        $menus = [];
        if ($this['is_super']) {
            $menus = SystemMenusModel::select()->toArray();
        } else {
            foreach ($this->role() as $role) {
                foreach ($role as $menu) {
                    $menus[] = $menu->toArray();
                }
            }
        }
        return array_values(array_column($menus, null, 'id'));
    }
}