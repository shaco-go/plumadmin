<?php

namespace app\adminapi\controller\system;

use app\adminapi\Controller;
use app\adminapi\service\system\RoleService;
use app\adminapi\validate\system\RoleValidate;
use app\model\system\SystemRoleModel;
use plum\exception\FailException;
use plum\utils\Arr;

class Role extends Controller
{
    /**
     * 分页
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/28
     */
    public function page()
    {
        $page = SystemRoleModel::autoOrder()
            ->autoSearch()
            ->paginate();
        trace('[角色] - [分页]','op');
        return $this->success($page);
    }

    /**
     * 列表
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/28
     */
    public function list()
    {
        $page = SystemRoleModel::autoOrder()
            ->autoSearch()
            ->select();
        trace('[角色] - [列表]','op');
        return $this->success($page);
    }

    /**
     * 创建
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function create()
    {
        validate(RoleValidate::class)
            ->scene('create')
            ->check($this->request->param());
        RoleService::create($this->request->param());
        trace('[角色] - [新增]','op');
        return $this->success('操作成功');
    }

    /**
     * 修改
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function update()
    {
        validate(RoleValidate::class)
            ->scene('update')
            ->check($this->request->param());
        RoleService::update($this->request->param());
        trace('[角色] - [更新]','op');
        return $this->success('操作成功');
    }

    /**
     * 详情
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function detail()
    {
        $detail = SystemRoleModel::findOrFail($this->request->param('id'));
        $detail['rule_ids'] = Arr::pluck($detail->rule, 'id');
        $detail->hidden(['rule']);
        trace('[角色] - [详情]','op');
        return $this->success($detail);
    }

    /**
     * 删除
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function delete()
    {
        $detail = SystemRoleModel::find($this->request->param('id'));
        if(!$detail)
            throw new FailException('操作失败');
        $detail->delete();
        trace('[角色] - [删除]','op');
        return $this->success('操作成功');
    }
}