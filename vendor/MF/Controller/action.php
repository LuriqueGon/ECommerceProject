<?php

    namespace MF\Controller;
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
                if(file_exists("../app/View/pages/$atualClass/".$this->view->page.".phtml"))
                {
                require_once "../app/View/pages/$atualClass/".$this->view->page.".phtml";
            }else

            {
                require_once "../app/View/Configs/404Error.phtml";
            }
        }

        protected function loadComponent($component)
        {
            $atualClass =  strtolower(str_replace('Controller', '',str_replace('App\\Controllers\\', '', get_class($this)))); 
            $thisClass['component'] = "../app/View/components/";
            $thisClass['extension'] = ".phtml";

            if(file_exists($thisClass['component']."$atualClass/$component".$thisClass['extension']))
            {
                require_once $thisClass['component']."$atualClass/$component".$thisClass['extension'];
            }

            else if(file_exists($thisClass['component']."main/$component".$thisClass['extension']))
            {
                require_once $thisClass['component']."main/$component".$thisClass['extension'];
            }

            else if(file_exists($thisClass['component']."url/$component".$thisClass['extension']))
            {
                require_once $thisClass['component']."url/$component".$thisClass['extension'];
            }
            
            else if(file_exists($thisClass['component']."meta/$component".$thisClass['extension']))
            {
                require_once $thisClass['component']."meta/$component".$thisClass['extension'];
            }

            else if(file_exists($thisClass['component']."config/$component".$thisClass['extension']))
            {
                require_once $thisClass['component']."config/$component".$thisClass['extension'];
            }

            else
            {
                require_once "../app/View/pages/Configs/404Error".$thisClass['extension'];
            }
        }

    }

?>