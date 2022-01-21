<?php

namespace plum\exception;

class AuthException extends Exception
{
    public $message = 'NOT LOGGED IN';
    public $code = 401;
}