<?php

namespace app\adminapi\controller\system;

use app\adminapi\Controller;
use app\adminapi\service\system\AttachmentService;
use app\adminapi\validate\system\AttachmentValidate;
use app\model\system\SystemAttachmentModel;
use app\model\system\SystemConfigModel;
use think\facade\Filesystem;

class Attachment extends Controller
{
    /**
     * 分页
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function page()
    {
        $page = SystemAttachmentModel::autoOrder()
            ->autoSearch()
            ->append(['url'])
            ->paginate();
        return $this->success($page);
    }

    /**
     * 上传
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function upload()
    {
        $file = AttachmentService::upload();
        return $this->success($file);
    }

    /**
     * 转移
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function move()
    {
        validate(AttachmentValidate::class)
            ->scene('move')
            ->check($this->request->param());
        SystemAttachmentModel::whereIn('id', $this->request->param('ids'))
            ->update(['category_id' => $this->request->param('category_id')]);
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
        $list = SystemAttachmentModel::whereIn('id', $this->request->param('ids'));
        $list = $list->select();
        $list->delete();
        //删除文件
        foreach ($list as $v) {
            $path = $v['path'];
            $driver = $v['driver'];
            Filesystem::disk($driver)->delete($path);
        }
        return $this->success('操作成功');
    }
}