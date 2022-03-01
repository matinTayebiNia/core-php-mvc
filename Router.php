<?php

namespace matintayebi\phpmvc;


use matintayebi\phpmvc\exception\notFoundException;

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];

    /**Route Constructor
     *
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }


    public function get(string $path, callable|array|string $callback)
    {
        $this->routes["get"][$path] = $callback;
    }

    public function post(string $path, callable|array $callback)
    {
        $this->routes["post"][$path] = $callback;
    }

    /**
     * @throws notFoundException
     */
    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;
        if ($callback === false) {
            throw new notFoundException();
        }

        if (is_string($callback)) {
            return Application::$app->view->renderView($callback);
        }
        if (is_array($callback)) {

            /** @var Controller $controller */
            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            foreach ($controller->getMiddleware() as $middleware) {
                $middleware->execute();
            }
            $callback[0] = $controller;
        }

        return call_user_func($callback, $this->request, $this->response);
    }

}