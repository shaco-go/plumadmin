<?php

namespace app\adminapi\controller\system;

use app\adminapi\Controller;
use app\model\system\SystemConfigModel;

class Config extends Controller
{
    /**
     * 配置上传设置
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/12
     */
    public function setAttachment()
    {
        $data = $this->request->param('data/a', []);
        SystemConfigModel::setItem('filesystem', $data);
        return $this->success('操作成功');
    }

    /**
     * 获取上传设置
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/12
     */
    public function getAttachment()
    {
        $data = SystemConfigModel::getItem('filesystem');
        //本地的数据,需要动态获取,所以删除掉动态模块
        unset($data['disks']['local']);
        return $this->success($data);
    }

    /**
     * 配置上传限制设置
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/12
     */
    public function setAttachmentValidate()
    {
        $data = $this->request->param('data/a', []);
        SystemConfigModel::setItem('filesystem_valid', $data);
        return $this->success('操作成功');
    }

    /**
     * 获取上传限制设置
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/12
     */
    public function getAttachmentValidate()
    {
        $data = SystemConfigModel::getItem('filesystem_valid');
        //本地的数据,需要动态获取,所以删除掉动态模块
        return $this->success($data);
    }
}