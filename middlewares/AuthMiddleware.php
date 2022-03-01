<?php

namespace app\core\middlewares;

use app\core\Application;
use app\core\exception\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{
    protected array $action = [];

    public function __construct(array $action = [])
    {
        $this->action = $action;
    }

    /**
     * @throws ForbiddenException
     */
    public function execute()
    {
        if (Application::isGuest()) {
            if (empty($this->action) || in_array(Application::$app->controller->action, $this->action)) {
                throw new ForbiddenException();
            }
        }
    }
}