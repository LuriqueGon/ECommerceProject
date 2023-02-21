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

            $routes['homeAdmin'] = array(
                'route' => '/admin',
                'controller' => 'IndexController',
                'action' => 'indexAdmin'
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
            
            $routes['forgotPassword'] = array(
                'route' => '/login/forgotPassword',
                'controller' => 'AuthController',
                'action' => 'forgotPasswordPages'
            ); 
            
            $routes['forgotReset'] = array(
                'route' => '/login/forgotPassword/reset',
                'controller' => 'AuthController',
                'action' => 'forgotReset'
            ); 
            $routes['resetPassword'] = array(
                'route' => '/login/forgotPassword/resetPassword',
                'controller' => 'AuthController',
                'action' => 'resetPassword'
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
                'route' => '/produtos',
                'controller' => 'ProductController',
                'action' => 'index'
            );  
            
            $routes['loadCategory'] = array(
                'route' => '/produtos',
                'controller' => 'ProductController',
                'action' => 'index'
            );  
            
            $routes['productsDetails'] = array(
                'route' => '/productsDetails',
                'controller' => 'ProductController',
                'action' => 'details'
            );  

            $routes['createProducts'] = array(
                'route' => '/admin/produto/create',
                'controller' => 'ProductController',
                'action' => 'loadCreate'
            );  

            $routes['cadastrarProduto'] = array(
                'route' => '/admin/product/create',
                'controller' => 'ProductController',
                'action' => 'create'
            );  

            $routes['listarProdutos'] = array(
                'route' => '/admin/produtos',
                'controller' => 'ProductController',
                'action' => 'listar'
            ); 

            $routes['deletarProduto'] = array(
                'route' => '/admin/produto/delete',
                'controller' => 'ProductController',
                'action' => 'delete'
            );  
            
            $routes['editProduct'] = array(
                'route' => '/admin/produto/edit',
                'controller' => 'ProductController',
                'action' => 'loadUpdate'
            );  
            
            $routes['updateProduct'] = array(
                'route' => '/admin/product/editar',
                'controller' => 'ProductController',
                'action' => 'update'
            );  
            
            
            
            
            
            // PAYMENT
            $routes['pagamento'] = array(
                'route' => '/pagamento',
                'controller' => 'PayController',
                'action' => 'index'
            );  
            
            // USERS
            $routes['allUsers'] = array(
                'route' => '/admin/users',
                'controller' => 'UserController',
                'action' => 'allUsers'
            );  
            
            $routes['cadastrarUser'] = array(
                'route' => '/admin/users/create',
                'controller' => 'UserController',
                'action' => 'cadastrarUser'
            );  

            $routes['ativarUser'] = array(
                'route' => '/admin/users/ativar',
                'controller' => 'UserController',
                'action' => 'ativarUser'
            );  
            
            $routes['desativarUser'] = array(
                'route' => '/admin/users/desative',
                'controller' => 'UserController',
                'action' => 'desativarUser'
            );  

            $routes['deletarUser'] = array(
                'route' => '/admin/user/delete',
                'controller' => 'UserController',
                'action' => 'deletarUser'
            );  
            
            $routes['editarUser'] = array(
                'route' => '/admin/users/edit',
                'controller' => 'UserController',
                'action' => 'editarUser'
            );  
            
            $routes['salvarEdition'] = array(
                'route' => '/admin/users/edit/session',
                'controller' => 'UserController',
                'action' => 'salvarEdition'
            );  
            
            // Categorie
            $routes['allCategorie'] = array(
                'route' => '/admin/categorias',
                'controller' => 'CategorieController',
                'action' => 'index'
            ); 

            $routes['categoriaCreate'] = array(
                'route' => '/admin/categoria/create',
                'controller' => 'CategorieController',
                'action' => 'loadCreate'
            );  
            
            $routes['criarCategoria'] = array(
                'route' => '/admin/categoria/criar',
                'controller' => 'CategorieController',
                'action' => 'create'
            );  
            
            $routes['editarCategoriaPage'] = array(
                'route' => '/admin/categoria/edit',
                'controller' => 'CategorieController',
                'action' => 'loadUpdate'
            );  
            
            $routes['editarCategoria'] = array(
                'route' => '/admin/categoria/editar',
                'controller' => 'CategorieController',
                'action' => 'update'
            );  
            
            $routes['deletarCategoria'] = array(
                'route' => '/admin/categoria/delete',
                'controller' => 'CategorieController',
                'action' => 'delete'
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