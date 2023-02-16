<?php

    namespace MF\Controller;

    abstract class ControllerConfig 
    {

        protected $view;   

        public function __construct()
        {
            $this->view = new \stdClass();
            $this->fixUrls();
            $this->getAll();
            session_start();
        }

        protected function getAll()
        {
            $this->getBootstrap();
            $this->getFontAwesome(); 
            $this->getJquery();      
        }

        protected function getBootstrap():Array
        {
            return $this->view->bootstrap;
        }

        protected function getFontAwesome():Array
        {
            return $this->view->fontAwesome;
        }

        protected function getJquery():Array
        {
            return $this->view->jquery;
        }
        
        protected function fixUrls()
        {
            // Bootstrap
            $this->view->bootstrap = array(
                "css" => "https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css",
                "js" => array(
                    "https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js",
                    "https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
                )
            );

            // FontAwesome

            $this->view->fontAwesome = array(
                "https://pro.fontawesome.com/releases/v5.10.0/css/all.css",
                "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
            );

            // Jquery

            $this->view->jquery = array(
                "https://code.jquery.com/jquery-3.3.1.slim.min.js"
            );

        }
        
        
    }

?>