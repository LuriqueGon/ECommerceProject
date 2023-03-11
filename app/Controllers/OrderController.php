<?php 

    namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

    class OrderController extends Action
    {
        public function index()
        {
            $this->restrict();

            $order = Container::getModel('order');
            $order->__set('iduser', $_SESSION['User']['iduser']);

            $this->view->orders = $order->getOrders();

            $this->view->title = "Meus pedidos";
            $this->render('pedidos');
        }

        public function details()
        {
            $this->restrict();

            $order = Container::getModel('order');
            $order->__set('idorder', $_GET['idorder']);
            $this->view->order = $order->getOrder();

            $cart = Container::getModel('cart');
            $cart->__set('idCart', $this->view->order['idcart']);
            $this->view->products = $cart->getAllProductsOrder();

            $this->view->title = "Detalhes do Pedido Nº: ". $_GET['idorder'];
            $this->render('pedidosDetails');
        }

        
    }

?>