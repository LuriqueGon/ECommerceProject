<?php 

    namespace App\Controllers;
    use MF\Controller\Action;

    class CartController extends Action
    {
        public function index()
        {
            $this->restrict();
            $this->view->title = "Carrinho";
            $this->render('cart');
        }
    }

?>