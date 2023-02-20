<?php 

    namespace App\Controllers;
    use MF\Controller\Action;
    use MF\Model\Container;

    class IndexController extends Action
    {

        public function index()
        {
            $this->view->title = "Home";    
            $this->render('index');
            
        }

        public function indexAdmin()
        {
            $this->restrict();
            $this->inAdmin();
            $this->view->title = "Home";    
            $this->render('indexAdmin', 'adminLayout');
        }
    }

?>