<?php

namespace app\core;

class View
{
    public string $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function renderView(string $view, array $params = []): string|array
    {
        $viewContent = $this->renderOnlyView($view, $params);
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }


    public function layoutContent(): bool|string
    {
        $layout = Application::$app->layout;
        if (isset(Application::$app->controller->layout)) {
            $layout = Application::$app->controller->layout;
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        return ob_get_clean();
    }

    protected function renderOnlyView(string $view, array $params): bool|string
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/views/" . $view . ".php";
        return ob_get_clean();
    }

}