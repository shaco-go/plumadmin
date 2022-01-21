<?php

namespace plum\exception;

class PermissionException extends Exception
{
    public $message = 'PERMISSION FORBIDDEN';
    public $code = 403;
}