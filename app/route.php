<?php

namespace App; 
use MF\Init\Bootstrap; 

    class Route extends Bootstrap
    {

        protected function initRoutes()
        {
            $routes = array();
            $routes = $this->loadAllRoutes($routes);
            $this->setRoutes($routes);
        }

        
    }

?>