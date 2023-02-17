<?php

    namespace MF\Controller;

use App\Models\Message;
use MF\Controller\ControllerConfig;

    abstract class Action extends ControllerConfig
    
    {

        protected function render($view, $layout = 'layout')
        {
            $this->view->page = $view;

            if(file_exists("../app/View/layouts/$layout.phtml"))
            {
                require_once "../app/View/layouts/$layout.phtml";
            }else
            
            {
                require_once "../app/View/Configs/404Error.phtml";
            }
        }

        protected function content()
        {
            $atualClass =  strtolower(str_replace('Controller', '',str_replace('App\\Controllers\\', '', get_class($this)))); 
            
            if(file_exists("../app/View/pages/$atualClass/".$this->view->page.".phtml")){
                require_once "../app/View/pages/$atualClass/".$this->view->page.".phtml";
            }
            else{
                require_once "../app/View/Configs/404Error.phtml";
            }
        }

        protected function loadComponent($component)
        {
            $atualClass =  strtolower(str_replace('Controller', '',str_replace('App\\Controllers\\', '', get_class($this)))); 
            $thisClass['component'] = "../app/View/components/";
            $thisClass['extension'] = ".phtml";

            if(file_exists($thisClass['component']."$atualClass/$component".$thisClass['extension'])){
                require_once $thisClass['component']."$atualClass/$component".$thisClass['extension'];
            }

            else if(file_exists($thisClass['component']."main/$component".$thisClass['extension'])){
                require_once $thisClass['component']."main/$component".$thisClass['extension'];
            } 

            else if (file_exists($thisClass['component'] . "app/$component" . $thisClass['extension'])) {
                require_once $thisClass['component'] . "app/$component" . $thisClass['extension'];
            }

            else if(file_exists($thisClass['component']."url/$component".$thisClass['extension'])){
                require_once $thisClass['component']."url/$component".$thisClass['extension'];
            }
            
            else if(file_exists($thisClass['component']."meta/$component".$thisClass['extension'])){
                require_once $thisClass['component']."meta/$component".$thisClass['extension'];
            }

            else if(file_exists($thisClass['component']."config/$component".$thisClass['extension'])){
                require_once $thisClass['component']."config/$component".$thisClass['extension'];
            }

            else{
                require_once "../app/View/pages/Configs/404Error".$thisClass['extension'];
            }
        }

        public function restrict(){
            if(!isset($_SESSION['auth'])){
                Message::setMessage('Você precisa está logado para ter acesso a página restrita','danger','/access');
            }
            if(!$_SESSION['auth']){
                Message::setMessage('Você precisa está logado para ter acesso a página restrita','danger','/access');
            }
        }
    
        public function dontRestrict(){
            if(isset($_SESSION['auth']) && $_SESSION['auth']){
                Message::setMessage('Você já está logado, caso queira trocar de conta. Clique em sair','danger','/');
                exit;
            }
        }

        protected function setValueObject(Object $object, Array $values):Object{

            foreach ($values as $key => $value) {
                $object->__set($key, $value);
            }

            return $object;
        }

        protected function setValueArray(Array $array, Array $values, Array $excepitions = array()):Array
        {
            foreach ($values as $key => $value) {
                $array[$key] = $value;

                foreach ($excepitions as $excepition) {
                    if($key == $excepition) unset($array[$key]); 
                    
                }
            }

            return $array;
        }

        protected function unsetValueArray(Array $array):array
        {   
            foreach ($array as $key => $value) {
                unset($array[$key]);
            }
            return $array;
        }

        protected function needPOST(Array $post){
            if(isset($post) && !empty($post)) return true;

            Message::setMessage('Você não tem permissão para acessar a página', 'danger');
        }


    }

?>