<?php

namespace App; 
use MF\Init\Bootstrap; 

    class Route extends Bootstrap
    {

        protected function initRoutes()
        {
            $routes = array();
            // INDEX
            $routes = $this->loadRoutes('index', $routes);
            
            // AUTH
            $routes = $this->loadRoutes('auth', $routes);
            
            // ADDRESS
            $routes = $this->loadRoutes('address', $routes);

            // CART
            $routes = $this->loadRoutes('cart', $routes);
            
            // PRODUCT
            $routes = $this->loadRoutes('products', $routes);
            
            // COMENT
            $routes = $this->loadRoutes('coment', $routes);
            
            // USERS
            $routes = $this->loadRoutes('users', $routes);
            
            // CATEGORIE
            $routes = $this->loadRoutes('categorie', $routes);

            $this->setRoutes($routes);
        }

        
    }

?>