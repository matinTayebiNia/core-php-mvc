<?php

namespace matintayebi\phpmvc;

class Response
{
    public function setStatusCode(int $code)
    {
        http_response_code($code);

    }

    public function redirect(string $root)
    {
        header("location: " . $root);
    }
}