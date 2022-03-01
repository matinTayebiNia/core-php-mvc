<?php

namespace matintayebi\phpmvc\exception;

use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

class ForbiddenException extends \Exception
{
    protected $message = "you don't have permission to access this page ";
    protected $code = 403;


}