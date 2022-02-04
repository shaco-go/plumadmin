<?php

namespace app\adminapi\controller\system;

use app\adminapi\Controller;
use app\adminapi\service\system\AdminService;
use app\adminapi\validate\system\AdminValidate;
use app\model\system\SystemAdminModel;
use plum\exception\FailException;
use plum\utils\Attachment;
use think\helper\Arr;

class Admin extends Controller
{
    /**
     * 分页
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function page()
    {
        $page = SystemAdminModel::autoSearch()
            ->autoOrder()
            ->paginate()
            ->toArray();
        Attachment::getItem($page['data'], ['avatar']);
        trace('[管理员] - [分页]','op');
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
        $data = $this->request->param();
        validate(AdminValidate::class)
            ->scene('create')
            ->check($data);
        Attachment::getId($data['avatar']);
        AdminService::create($data);
        trace('[管理员] - [新增]','op');
        return $this->success('操作成功');
    }

    /**
     * 编辑
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function update()
    {
        $data = $this->request->param();
        validate(AdminValidate::class)
            ->scene('update')
            ->check($data);
        Attachment::getId($data['avatar']);
        AdminService::update($data);
        trace('[管理员] - [更新]','op');
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
        $detail = SystemAdminModel::findOrFail($this->request->param('id'));
        $detail->hidden(['password']);
        Attachment::getItem($detail, ['avatar']);
        $detail['role_ids'] = Arr::pluck($detail->role,'id');
        trace('[管理员] - [详情]','op');
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
        $detail = SystemAdminModel::find($this->request->param('id'));
        if (!$detail)
            throw new FailException('操作失败');
        $detail->delete();
        trace('[管理员] - [删除]','op');
        return $this->success('操作成功');
    }
}