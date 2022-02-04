<?php

namespace app\adminapi\controller\system;

use app\adminapi\Controller;
use app\model\system\SystemDebugLogModel;
use plum\exception\FailException;
use think\facade\Db;

class Debug extends Controller
{
    /**
     * 分页
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/17
     */
    public function page()
    {
        $page = SystemDebugLogModel::autoOrder()
            ->autoSearch()
            ->paginate();
        trace('[DEBUG日志] - [分页]','op');
        return $this->success($page);
    }

    /**
     * 删除
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/17
     */
    public function delete()
    {
        $debug = SystemDebugLogModel::find(input('id'));
        if(!$debug)
            throw new FailException('操作失败');
        $debug->delete();
        trace('[DEBUG日志] - [删除]','op');
        return $this->success('操作成功');
    }

    /**
     * 清除所有的信息
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/17
     */
    public function clear()
    {
        Db::table('system_debug_log')
            ->delete(true);
        trace('[DEBUG日志] - [清除]','op');
        return $this->success('操作成功');
    }
}