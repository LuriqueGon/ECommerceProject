<?php

namespace App; 
use MF\Init\Bootstrap; 

    class Route extends Bootstrap
    {

        protected function initRoutes()
        {

            $routes['home'] = array(
                'route' => '/',
                'controller' => 'IndexController',
                'action' => 'index'
            );  
            
            $routes['teste'] = array(
                'route' => '/teste',
                'controller' => 'IndexController',
                'action' => 'teste'
            );  
            
            $routes['login'] = array(
                'route' => '/login',
                'controller' => 'AuthController',
                'action' => 'index'
            );  
            
            $routes['forgetPassword'] = array(
                'route' => '/login/forgetPassword',
                'controller' => 'AuthController',
                'action' => 'forgetPassword'
            );  

            $routes['cart'] = array(
                'route' => '/cart',
                'controller' => 'CartController',
                'action' => 'index'
            );  
            
            $routes['products'] = array(
                'route' => '/products',
                'controller' => 'ProductController',
                'action' => 'index'
            );  
            
            
            
            /*
            $routes['NomeDaRota'] = array(
                'route' => '/EndereçoDaRota',
                'controller' => 'NomeDoController',
                'action' => 'MétodoDentroDoController'
            );  
            */
            
            

            $this->setRoutes($routes);
        }

        
    }

?>