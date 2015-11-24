<?php namespace GlobsTracking\Globs;

require_once 'GlobsTracking/vendor/Twig/lib/Twig/Autoloader.php';

class FrontController
{
    const DEFAULT_CONTROLLER = "Index";
    const DEFAULT_ACTION = "index";

    private $controller = self::DEFAULT_CONTROLLER;
    private $action = self::DEFAULT_ACTION;
    private $params = array();
    private $base_path = "/";
    private $controller_path = "\\GlobsTracking\\Globs\\Controllers\\";
    protected $twig;

    public function __construct()
    {
        $this->parseUri();

        \Twig_Autoloader::register();
        $loader = new \Twig_Loader_Filesystem(__DIR__ . "/Views");
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => false,
            'debug' => true,
            'strict_variables' => true
        ));
    }

    public function setController($controller)
    {
        $controller = ucfirst(strtolower($controller)) . "Controller";
        $class = $this->controller_path . $controller;
        if (!class_exists($class) || $controller === "FrontController" ) {
            throw new \InvalidArgumentException(
                "The action controller '$controller' has not been defined."
            );
        }
        $this->controller = $controller;
        return $this;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setAction($action)
    {
        $reflector = new \ReflectionClass($this->controller_path . $this->controller);
        if (!$reflector->hasMethod($action)) {
            throw new \InvalidArgumentException(
                "The controller action '$action' has not been defined."
            );
        }
        $this->action = $action;
        return $this;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    public function getParams()
    {
        return $this->params;
    }

    private function parseUri()
    {
        $path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
        $path = preg_replace('/[^a-zA-Z0-9]\//', "", $path);
        if (strpos($path, $this->base_path) === 0) {
            $path = substr($path, strlen($this->base_path));
        }
        @list($controller, $action, $params) = explode("/", $path, 3);

        if (isset($controller) && !empty($controller)) {
            $this->setController($controller);
        } else {
            $this->setController($this->controller);
        }

        if (isset($action)) {
            $this->setAction($action);
        }

        if (isset($params)) {
            $this->setParams(explode("/", $params));
        }
    }

    public function run()
    {
        $class = $this->controller_path . $this->controller;
        call_user_func_array(array(new $class, $this->action), $this->params);
    }

}