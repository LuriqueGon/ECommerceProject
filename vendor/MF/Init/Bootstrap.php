<?php 

    namespace MF\Init;

    abstract class Bootstrap
    {
        private $routes;
        private $diretorio;

        abstract protected function initRoutes();

        public function __construct()
        {
            $this->diretorio = dir('../app/Routes/');
            $this->initRoutes();
            $this->run($this->getUrl());
        }

        public function __destruct()
        {
            $this->diretorio->close();
        }

        public function setRoutes(array $routes){
            $this->routes = $routes;
        }

        public function getRoutes()
        {
            return $this->routes;
        }

        protected function getUrl(){
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
            exit;
        }

        private function loadRoutes($routePath, $routes)
        {
            $routePath = strtolower($routePath);
            
            if(file_exists("../app/Routes/$routePath.routes.phtml"))return $this->loadRoute($routePath, $routes);
        }

        private function loadRoute($routePath, $routes)
        {
            require "../app/Routes/$routePath.routes.phtml";
            foreach ($routesRoutes as $key => $route)$routes[$key] = $route;
            return $routes;
        }

        private function loadPath($routes)
        {
            $allRoutes = [];
            while(($arquivo = $this->diretorio->read())) 
            {
                if($arquivo != '.' && $arquivo != '..'){
                     array_push($allRoutes,$this->loadRoutes($this->getRouteName($arquivo), $routes));
                }
            }

            return $allRoutes;
        }

        private function getRouteName($route):String{
            return str_replace('.routes.phtml','',$route);
        }

        protected function loadAllRoutes($routes)
        {
            $allRoutes = $this->loadPath($routes);
            
            foreach($allRoutes as $formRoutes)foreach ($formRoutes as $key => $route) $routes[$key] = $route;
            return $routes;


        }

    }
    

?>