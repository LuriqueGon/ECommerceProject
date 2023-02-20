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
            $this->view->phpMailer['name'] = "Contato E-Commerce";
            $this->view->phpMailer['port'] = 587;
            $this->view->phpMailer['smtpAuth'] = true;
            $this->view->phpMailer['smtpSecure'] = "tls";
            $this->view->phpMailer['emailCopy'] = "gamenizados@gmail.com";
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
                "css" => "/css/bootstrap.min.css",
                "js" => array(
                    "http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"
                )
            );

            // FontAwesome

            $this->view->fontAwesome = array(
                "https://pro.fontawesome.com/releases/v5.10.0/css/all.css",
                "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css",
                '/css/font-awesome.min.css'
            );

            // Jquery

            $this->view->jquery = array(
                "https://code.jquery.com/jquery.min.js"
            );

            $this->view->fonts = array(
                'http://fonts.googleapis.com/css?family=Titillium+Web:400,200,300,700,600',
                'http://fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300',
                'http://fonts.googleapis.com/css?family=Raleway:400,100'
            );

        }

        private function constructConsts(){
            // PHPMAILER

            $this->view->phpMailer['emailHost'] = "smtp.gmail.com";
            $this->view->phpMailer['emailSiteAdmin'] = "gamenizados@gmail.com";
            $this->view->phpMailer['user'] = "";
            $this->view->phpMailer['name'] = "Contato E-Commerce";
            $this->view->phpMailer['password'] = "hfkmjxeiqxyywgsf";
            $this->view->phpMailer['port'] = 587;
            $this->view->phpMailer['smtpAuth'] = true;
            $this->view->phpMailer['smtpSecure'] = "tls";
            $this->view->phpMailer['emailCopy'] = "gamenizados@gmail.com";
        }
        
        
    }

?>