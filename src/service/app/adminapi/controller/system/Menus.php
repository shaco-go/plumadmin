<?php

namespace app\adminapi\controller\system;

use app\adminapi\Controller;
use app\adminapi\validate\system\MenusValidate;
use app\model\system\SystemMenusModel;
use plum\exception\FailException;
use plum\utils\Arr;
use plum\utils\Helper;

class Menus extends Controller
{
    /**
     * 树状
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/28
     */
    public function tree()
    {
        $currentRuleIds = array_column($this->userinfo->getAllRule(), 'id');
        $list = SystemMenusModel::autoOrder()
            ->whereIn('id', $currentRuleIds)
            ->select()
            ->toArray();
        $tree = Arr::tree($list);
        trace('[菜单] - [树状数据]','op');
        return $this->success($tree);
    }

    /**
     * 新增
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/28
     */
    public function create()
    {
        validate(MenusValidate::class)
            ->scene('create')
            ->check($this->request->param());
        SystemMenusModel::create($this->request->param());
        trace('[菜单] - [新增]','op');
        return $this->success('操作成功');
    }

    /**
     * 修改
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/28
     */
    public function update()
    {
        validate(MenusValidate::class)
            ->scene('update')
            ->check($this->request->param());
        SystemMenusModel::update($this->request->param());
        trace('[菜单] - [更新]','op');
        return $this->success('操作成功');
    }

    /**
     * 规则信息
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/28
     */
    public function detail()
    {
        $detail = SystemMenusModel::findOrFail($this->request->param('id'));
        trace('[菜单] - [详情]','op');
        return $this->success($detail);
    }

    /**
     * 删除
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/28
     */
    public function delete()
    {
        $ids = Helper::getChildrenIds(new SystemMenusModel(), $this->request->param('ids/a'));
        $menus = SystemMenusModel::whereIn('id',$ids)
            ->select();
        if ($menus->isEmpty())
            throw new FailException('操作失败');
        $menus->delete();
        trace('[菜单] - [删除]','op');
        return $this->success('操作成功');
    }
}