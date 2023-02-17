<?php 

    namespace App\Controllers;
    use MF\Controller\Action;
    use MF\Model\Container;

    class IndexController extends Action
    {

        public function index()
        {
            $this->view->title = "Home";    
            $admin = false;
            
            if(isset($_SESSION['inadmin']) && $_SESSION['inadmin'] == 1){
                $this->render('indexAdmin', 'adminLayout');
            }else{
                $this->render('index');
            }
            
        }
    }

?>