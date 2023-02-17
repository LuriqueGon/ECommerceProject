<?php

namespace App; 
use MF\Init\Bootstrap; 

    class Route extends Bootstrap
    {

        protected function initRoutes()
        {

            // INDEX
            $routes['home'] = array(
                'route' => '/',
                'controller' => 'IndexController',
                'action' => 'index'
            );  
           
            
            // AUTH
            $routes['page'] = array(
                'route' => '/login',
                'controller' => 'AuthController',
                'action' => 'index'
            );  

            $routes['login'] = array(
                'route' => '/auth/login',
                'controller' => 'AuthController',
                'action' => 'login'
            );  

            $routes['register'] = array(
                'route' => '/auth/register',
                'controller' => 'AuthController',
                'action' => 'register'
            );  
            
            $routes['forgetPassword'] = array(
                'route' => '/login/forgetPassword',
                'controller' => 'AuthController',
                'action' => 'forgetPasswordPages'
            );  

            $routes['logout'] = array(
                'route' => '/logout',
                'controller' => 'AuthController',
                'action' => 'logout'
            );  

            

            // CART

            $routes['cart'] = array(
                'route' => '/cart',
                'controller' => 'CartController',
                'action' => 'index'
            );  
            
            // PRODUCT
            $routes['products'] = array(
                'route' => '/products',
                'controller' => 'ProductController',
                'action' => 'index'
            );  
            
            $routes['productsDetails'] = array(
                'route' => '/productsDetails',
                'controller' => 'ProductController',
                'action' => 'details'
            );  
            
            // PAYMENT
             $routes['pagamento'] = array(
                'route' => '/pagamento',
                'controller' => 'PayController',
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