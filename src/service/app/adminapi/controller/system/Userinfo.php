<?php

namespace app\adminapi\controller\system;

use app\adminapi\Controller;
use app\adminapi\service\system\AdminService;
use app\adminapi\validate\system\AdminValidate;
use app\model\system\SystemMenusModel;
use plum\exception\FailException;
use plum\utils\Arr;
use plum\utils\Attachment;

class Userinfo extends Controller
{
    /**
     * 更新
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function patch()
    {
        $data = $this->request->param();
        //头像ID
        if (!empty($data['avatar'])) {
            Attachment::getId($data['avatar']);
        }
        //保存
        $this->userinfo
            ->allowField(['nickname', 'avatar'])
            ->save($data);
        trace('[个人信息] - [更新]','op');
        return $this->success('操作成功');
    }

    /**
     * 修改密码
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/13
     */
    public function updatePassword()
    {
        validate(AdminValidate::class)
            ->scene('password')
            ->check($this->request->param());
        AdminService::updateCurrentPassword($this->request->param('new_password'));
        trace('[个人信息] - [修改密码]','op');
        return $this->success('操作成功');
    }

    /**
     * 管理员信息
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function detail()
    {
        $detail = $this->userinfo;
        $rule = $detail->getAllRule();
        //排序
        $rule = Arr::columnSort($rule, 'sort');
        $menus = [];
        $routes = [];
        $permissions = [];
        foreach ($rule as $v) {
            //菜单(目录加路由)
            if ($v['type'] === SystemMenusModel::TYPE_DIRECTORY || $v['type'] === SystemMenusModel::TYPE_MENU) {
                $menus['list'][] = $v;
            }
            //路由
            if ($v['type'] === SystemMenusModel::TYPE_MENU) {
                $routes['list'][] = $v;
            }
            //权限
            if ($v['type'] === SystemMenusModel::TYPE_PERMISSION) {
                $permissions[] = $v['mark'];
            }
        }
        //如果菜单和路由都没有的话,那么提示添加菜单
        if(empty($menus['list']) || empty($routes['list'])){
            throw new FailException('用户无路由和菜单,请添加后登录!');
        }
        //树状化
        $menus['tree'] = Arr::tree($menus['list']);
        $routes['tree'] = Arr::tree($routes['list']);
        $detail['menus'] = $menus;
        $detail['routes'] = $routes;
        $detail['permissions'] = $permissions;
        $detail->hidden(['role']);
        Attachment::getItem($detail, ['avatar']);
        trace('[个人信息] - [详情]','op');
        return $this->success($detail);
    }
}