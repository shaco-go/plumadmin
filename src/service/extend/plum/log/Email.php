<?php

namespace plum\log;

use think\contract\LogHandlerInterface;

class Email implements LogHandlerInterface
{

    public function save(array $log): bool
    {
        // 如果没有开启或只有error日志则直接返回true
        if (!config('email.switch') || empty($log['error'])) {
            return true;
        }
        try {
            foreach($log['error'] as $error){
                $this->emailSend($error);
            }
        } catch (\Throwable $e) {
        }
        return true;
    }

    // 邮件发送
    public function emailSend($error)
    {
        $address = config('email.to_email');
        $serviceName = env('service_name');
        $date = date('Y-m-d H:i:s');
        $title = "{$serviceName}:服务异常";
        $message = $error['message']??'';
        $file = $error['file']??'';
        $content = <<<EOF
<style>
    .error-notice-container{
        background: #f3f3f3;
        width: 100%;
        height: 100%;
        padding: 20px;
        box-sizing: border-box;
        font-family: 'Nunito', Arial, Tahoma, Geneva, sans-serif;
    }
    .error-notice-box{
        padding: 20px;
        box-sizing: border-box;
        margin: auto;
        background: #fff;
    }
    .error-notice-title{
        margin: 10px 0;
        color: #fa541c;
        font-size: 48px;
        line-height: 55px;
        font-weight: 700;
        letter-spacing: -1.5px;
    }
    .error-notice-date{
        color: #585858;
        font-size: 24px;
        line-height: 32px;
        word-wrap: break-word;
        margin-bottom: 20px;
    }
    .error-notice-message{
        color: #585858;
        font-size: 20px;
        line-height: 32px;
        word-wrap: break-word;
    }
</style>
<div  class="error-notice-container">
    <div class="error-notice-box">
        <div class="error-notice-title">
            {$serviceName}
        </div>
        <div class="error-notice-date">
            {$date}
        </div>
        <div class="error-notice-message">
            {$message}
        </div>
        <div class="error-notice-message">
            {$file}
        </div>
    </div>
</div>
EOF;
        \plum\lib\Email::getInstance()->send($address, $title, $content);
    }
}