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

        protected function restrict($red = ''){
            if(!isset($_SESSION['auth'])){
                Message::setMessage('Você precisa está logado para ter acesso a página restrita','danger','/login?redirect='.$red);
            }
            if(!$_SESSION['auth']){
                Message::setMessage('Você precisa está logado para ter acesso a página restrita','danger','/login?redirect='.$red);
            }
        }
    
        protected function dontRestrict(){
            if(isset($_SESSION['auth']) && $_SESSION['auth']){
                Message::setMessage('Você já está logado, caso queira trocar de conta. Clique em sair','danger','/');
                exit;
            }
        }

        protected function inAdmin(){
            if(!isset($_SESSION['inadmin']) || $_SESSION['inadmin'] == 0){
                Message::setMessage('Você não tem permissão para acessar essa página.', 'dunger');
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

        protected function needGET(Array $get){
            if(isset($get) && !empty($get)) return true;

            Message::setMessage('Você não tem permissão para acessar a página', 'danger');
        }

        protected function transformWeightNumber(Float $peso)
        {
            if ($peso < 1) {
                $peso_formatado = number_format($peso * 1000, 3, "", "");
                return rtrim($peso_formatado, "0") . " g";
            } else {
                $peso_formatado = number_format($peso, 3, ".", "");
                if (strpos($peso_formatado, ".000") !== false) {
                    return number_format($peso, 0, ".", "") . " kg";
                } else {
                    return rtrim($peso_formatado, "0") . " kg";
                }
            }
        }

        
    }
