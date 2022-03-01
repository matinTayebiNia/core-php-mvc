<?php

namespace matintayebi\phpmvc\exception;

class notFoundException extends \Exception
{
    protected $code = 404;
    protected $message = "the page not found";
}