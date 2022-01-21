<?php

namespace plum\exception;

class FailException extends Exception
{
    public $code = 400;
    public $message = 'ERROR';
}
