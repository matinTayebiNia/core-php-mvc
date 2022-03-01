<?php

namespace matintayebi\phpmvc;


use matintayebi\phpmvc\db\Database;
use JetBrains\PhpStorm\Pure;

class Application
{
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public static Application $app;
    public ?Controller $controller = null;
    public Database $db;
    public ?UserModel $user;
    public string $userClass;
    public string $layout;
    public View $view;

    public function __construct($rootDirectory,  $config)
    {
        $this->userClass = $config["userClass"];
        $this->layout = $config["application_main_layout"];
        self::$ROOT_DIR = $rootDirectory;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session($config["session_lifeTime"]);
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config["db"]);
        $this->view = new View($config["app_name"]);
        $primaryValue = $this->session->get("user");
        if ($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        } else {
            $this->logout();
        }
    }

    #[Pure] public static function isGuest(): bool
    {
        return !self::$app->user;
    }

    public function run()
    {
        try {
            echo $this->router->resolve();
        } catch (\Exception $exception) {
            $this->response->setStatusCode($exception->getCode());

            echo $this->view->renderView("errors/_error", [
                "exception" => $exception,
            ]);
        }
    }


    public function login(UserModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set("user", $primaryValue);
    }

    public function logout()
    {
        $this->user = null;
        $this->session->remove("user");
    }

}