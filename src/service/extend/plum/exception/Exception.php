<?php

namespace plum\exception;

class Exception extends \think\Exception
{
    protected $message;
    protected $code;

    public function __construct($message = null, $code = null, \Throwable $previous = null)
    {
        $this->message = $message ?: $this->message;
        $this->code = $code ?: $this->code;
        parent::__construct($this->message, $this->code, $previous);
    }
}