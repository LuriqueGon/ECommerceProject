<?php 

    namespace App\Controllers;
    use MF\Controller\Action;
    use MF\Model\Container;

    class IndexController extends Action
    {

        public function index()
        {

            $user = Container::getModel('user');
            $this->view->users = $user->getAll();
            $this->render('index', 'layout');
            
        }
    }

?>