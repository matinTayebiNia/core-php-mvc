<?php

namespace app\core\exception;

class notFoundException extends \Exception
{
    protected $code = 404;
    protected $message = "the page not found";
}