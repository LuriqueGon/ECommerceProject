<?php 

    namespace App\Controllers;
    use MF\Controller\Action;
    use MF\Model\Container;

    class AuthController extends Action
    {

        public function index()
        {
            $this->view->title = "Autenticação";
            $this->render('login');
            
        }

        public function forgetPassword()
        {
            $this->view->title = "Esqueceu a sua Senha?";
            $this->render('forgetPassword');
        }
    }

?>