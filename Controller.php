<?php

namespace app\core;

use app\core\middlewares\BaseMiddleware;
use JetBrains\PhpStorm\Pure;

abstract class Controller
{
    /**
     * @var BaseMiddleware[]
     *
     */
    protected array $middleware = [];
    public string $action = "";
    public string $layout = "main";

    public function render(string $view, array $params = []): array|string
    {
        return $this->view()->renderView($view, $params);
    }

    public function view(): View
    {
        return Application::$app->view;
    }

    #[Pure] public function body(): array
    {
        return Application::$app->request->body();
    }

    public function setLayout(string $layout)
    {
        $this->layout = $layout;
    }

    public function setFlashMessages($key, $message)
    {
        return Application::$app->session->setFlash($key, $message);
    }

    public function redirect(string $root)
    {
        return Application::$app->response->redirect($root);
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {

        $this->middleware[] = $middleware;

    }

    /**
     * @return BaseMiddleware[]
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }

}