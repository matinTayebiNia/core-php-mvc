<?php

namespace matintayebi\phpmvc;

class Session
{
    protected const FLASH_KEY = "flash_messages";


    public function __construct(string $sessionLifetime)
    {

        $lifetime = strtotime("+{$sessionLifetime} minutes", 0);
        session_set_cookie_params($lifetime);
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

        foreach ($flashMessages as $key => &$flashMessage) {
            //mark to removed
            $flashMessage["remove"] = true;
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    public function setFlash($key, $message)
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            "remove" => false,
            "value" => $message,
        ];
    }

    public function getFlash($key)
    {
        return $_SESSION[self::FLASH_KEY][$key]["value"] ?? false;
    }

    public function __destruct()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            if ($flashMessage["remove"]) {
                unset($flashMessages[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;

    }

    public function set($kay, $value)
    {
        $_SESSION[$kay] = $value;
    }

    public function get($Kay)
    {
        return $_SESSION[$Kay] ?? false;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

}