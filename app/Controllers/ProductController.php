<?php 

    namespace App\Controllers;
    use MF\Controller\Action;

    class ProductController extends Action
    {
        public function index()
        {
            $this->view->title = "Listagem de Produtos";
            $this->render('product');
        }

        public function details()
        {
            $this->view->title = "Detalhes dos Produtos";
            $this->render('productsDetails');
        }
    }

?>