<?php 

    namespace App\Controllers;

use App\Models\Message;
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

        public function adminPedidos()
        {
            $this->restrict();
            $this->inAdmin();
            
            $order = Container::getModel('order');
            $this->view->orders = $order->getAll();

            $this->view->title = "Todos os pedidos";
            $this->render('/admin/pedidos', 'adminTable');
        }

        public function adminPedidoDetails()
        {
            $this->restrict();
            $this->inAdmin();

            if(!isset($_GET['idorder']) || $_GET['idorder']<1) Message::setMessage('Ocorreu um erro inesperado', 'danger', '/admin/orders');
            
            $order = Container::getModel('order');
            $order->__set('idorder', $_GET['idorder']);
            $this->view->order = $order->getOrder();

            $cart = Container::getModel('cart');
            $cart->__set('idCart', $this->view->order['idcart']);
            $this->view->products = $cart->getAllProductsOrder();

            // var_dump($this->view->order);
            
            $this->view->title = "Todos os pedidos";
            $this->render('/admin/pedido', 'adminLayout');
        }

        public function adminPedidoStatus()
        {
            $this->restrict();
            $this->inAdmin();

            if(!isset($_GET['idorder']) || $_GET['idorder']<1) Message::setMessage('Ocorreu um erro inesperado', 'danger', '/admin/orders');
            
            $order = Container::getModel('order');
            $order->__set('idorder', $_GET['idorder']);
            $this->view->order = $order->getOrder();

            $orderStatus = Container::getModel('orderStatus');
            $this->view->orderStatus = $orderStatus->listAll();
            
            $this->view->title = "Editar Status do Pedido";
            $this->render('/admin/pedidoStatus', 'adminLayout');
        }

        public function adminPedidoStatusSalvar()
        {
            $this->restrict();
            $this->inAdmin();
            $this->needPOST($_POST);

            if(!isset($_GET['idorder']) || $_GET['idorder']<1) Message::setMessage('Ocorreu um erro inesperado', 'danger', '/admin/orders');
            
            
            $order = Container::getModel('order');
            $order->__set('idorder', $_GET['idorder']);
            $order->__set('idstatus', $_POST['idstatus']);
            $order->setStatus();

            Message::setMessage('Status alterado com sucesso', 'success','/admin/order?idorder='.$_GET['idorder']);
            
        }

        public function orderDelete()
        {
            $this->restrict();
            $this->inAdmin();

            if(!isset($_GET['idorder']) || $_GET['idorder']<1) Message::setMessage('Ocorreu um erro inesperado', 'danger', '/admin/orders');
            
            
            $order = Container::getModel('order');
            $order->__set('idorder', $_GET['idorder']);
            $order->delete();

            Message::setMessage('Status deletado com sucesso', 'success','/admin/orders');
            
        }

        

        

        
        
    }

?>