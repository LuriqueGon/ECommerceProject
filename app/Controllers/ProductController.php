<?php 

    namespace App\Controllers;
    use MF\Controller\Action;
    use MF\Model\Model;

    class ProductController extends Action
    {
        public function index(){
            $this->view->title = "Listagem de Produtos";
            $this->render('product');
        }
    }

?>