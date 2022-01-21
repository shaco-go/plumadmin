<?php

namespace app\adminapi\controller\system;

use app\adminapi\Controller;
use app\model\system\SystemConfigModel;
use plum\exception\FailException;

class Email extends Controller
{
    /**
     * 设置
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/15
     */
    public function setConfig()
    {
        SystemConfigModel::setItem('email', input('data/a', []));
        return $this->success('操作成功');
    }

    /**
     * 获取
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/15
     */
    public function getConfig()
    {
        $data = SystemConfigModel::getItem('email');
        return $this->success($data);
    }

    /**
     * 发送邮件测试
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/15
     */
    public function testSend()
    {
        validate([
            'address|收件人' => 'require|email'
        ])->check($this->request->param());
        try {
            $email = \plum\lib\Email::getInstance();
            $email->send(input('address'), '发送邮件测试', '发送成功');
        } catch (\Throwable $e) {
            $email = \plum\lib\Email::getInstance();
            throw new FailException("发送失败,失败原因:" . $email->email->ErrorInfo);
        }
        return $this->success('发送成功');
    }
}