<?php

namespace plum\lib;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Email
{
    static private $handler;
    /**
     * @var PHPMailer
     */
    public $email;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    static public function getInstance()
    {
        if (!(self::$handler instanceof self)) {
            self::$handler = new self();
            $handler = self::$handler;
            self::$handler->email = new PHPMailer(true);
            //初始化配置
            //设定邮件编码
            self::$handler->email->CharSet = "UTF-8";
            // 调试模式输出
            self::$handler->email->SMTPDebug = SMTP::DEBUG_OFF;
            // 使用SMTP
            self::$handler->email->isSMTP();
            // SMTP服务器
            self::$handler->email->Host = config('email.smtp_host');
            // 允许 SMTP 认证
            self::$handler->email->SMTPAuth = true;
            // SMTP 用户名  即邮箱的用户名
            self::$handler->email->Username = config('email.username');
            // SMTP 密码  部分邮箱是授权码(例如163邮箱)
            self::$handler->email->Password = config('email.password');
            // 服务器端口 25 或者465 具体要看邮箱服务器支持
            self::$handler->email->Port = config('email.port');
            //发件人
            self::$handler->email->setFrom(config('email.from_email'),config('email.from_email_name'));
            //回复的时候回复给哪个邮箱,建议和发件人一致
            self::$handler->email->addReplyTo(config('email.from_email'),config('email.from_email_name'));
            // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
            self::$handler->email->isHTML(true);
        }
        return self::$handler;
    }

    /**
     * 发送
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/15
     */
    public function send($address,$title,$content)
    {
        if(is_array($address)){
            // 多收件人
            foreach($address as $ad){
                $this->email->addAddress($ad);
            }
        }else{
            // 单收件人
            $this->email->addAddress($address);
        }
        $this->email->Subject = $title;
        $this->email->Body    = $content;
        $this->email->send();
    }
}