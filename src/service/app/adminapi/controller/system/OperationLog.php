<?php

namespace app\adminapi\controller\system;

use app\adminapi\Controller;
use app\model\system\SystemOperationLogModel;
use plum\exception\FailException;
use think\facade\Db;

class OperationLog extends Controller
{
    /**
     * 分页
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/28
     */
    public function page()
    {
        $page = SystemOperationLogModel::autoOrder()
            ->autoSearch()
            ->paginate()
            ->each(function ($item) {
                $item->operator_name = $item->admin?$item->admin['nickname']:'未知';
            });
        trace('[操作日志] - [分页]','op');
        return $this->success($page);
    }

    /**
     * 删除
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/29
     */
    public function delete()
    {
        $info = SystemOperationLogModel::where('id',$this->request->param('id'))
            ->find();
        if(!$info)
            throw new FailException('操作失败');
        $info->delete();
        trace('[操作日志] - [删除]','op');
        return $this->success('操作成功');
    }

    /**
     * 清除
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/29
     */
    public function clear()
    {
        Db::table('system_operation_log')->delete(true);
        trace('[操作日志] - [清除]','op');
        return $this->success('操作成功');
    }
}