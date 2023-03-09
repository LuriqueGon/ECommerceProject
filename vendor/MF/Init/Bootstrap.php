<?php 

    namespace MF\Init;

    abstract class Bootstrap
    {
        private $routes;

        abstract protected function initRoutes();

        public function __construct()
        {
            $this->initRoutes();
            $this->run($this->getUrl());
        }

        public function setRoutes(array $routes)
        {
            $this->routes = $routes;
        }

        public function getRoutes()
        {
            return $this->routes;
        }

        protected function getUrl()
        {
            return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        }

        protected function run($url)
        {
            foreach($this->getRoutes() as $route)
            {
                if($url == $route['route'])
                {

                    $class = "App\\Controllers\\" . $route['controller'];
                    $controller = new $class();
                    $action = $route['action'];
                    $controller->$action();
                    exit;
                    
                }
            }

            require "../app/View/Configs/404Error.phtml";
        }

        protected function loadRoutes($routePath, $routes)
        {
            $routePath = strtolower($routePath);
            
            if(file_exists("../app/Routes/$routePath.routes.phtml"))return $this->loadRoute($routePath, $routes);
        }

        protected function loadRoute($routePath, $routes)
        {
            require "../app/Routes/$routePath.routes.phtml";
            foreach ($routesRoutes as $key => $route)$routes[$key] = $route;
            return $routes;
        }

    }
    

?>