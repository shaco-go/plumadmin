<?php

namespace app\adminapi\controller\system;

use app\adminapi\Controller;
use app\adminapi\validate\system\AttachmentCategoryValidate;
use app\model\system\SystemAttachmentCategoryModel;
use app\model\system\SystemAttachmentModel;
use plum\utils\Arr;
use plum\utils\Helper;

class AttachmentCategory extends Controller
{
    /**
     * 获取树状
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function tree()
    {
        $list = SystemAttachmentCategoryModel::order('sort', 'asc');
        $list = $list->select();
        $tree = Arr::tree($list->toArray());
        trace('[附件分类] - [树状数据]','op');
        return $this->success($tree);
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
        validate(AttachmentCategoryValidate::class)
            ->scene('create')
            ->check($data);
        $data['operator_id'] = $this->userinfo['id'];
        SystemAttachmentCategoryModel::create($data);
        trace('[附件分类] - [新增]','op');
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
        $data = $this->request->param();
        validate(AttachmentCategoryValidate::class)
            ->scene('update')
            ->check($data);
        $data['operator_id'] = $this->userinfo['id'];
        SystemAttachmentCategoryModel::update($data);
        trace('[附件分类] - [更新]','op');
        return $this->success('操作成功');
    }

    /**
     * 删除
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function delete()
    {
        $ids = Helper::getChildrenIds(new SystemAttachmentCategoryModel(), $this->request->param('id'));
        $list = SystemAttachmentCategoryModel::whereIn('id', $ids);
        if (!$this->userinfo['is_super'])
            //非管理员只可删除自身的分类
            $list = $list->where('operator_id', $this->userinfo['id']);
        $list = $list->select();
        $list->delete();
        //将图片分类转化未分类
        SystemAttachmentModel::whereIn('parent_id', $ids)
            ->update(['parent_id' => 0]);
        trace('[附件分类] - [删除]','op');
        return $this->success('操作成功');
    }
}