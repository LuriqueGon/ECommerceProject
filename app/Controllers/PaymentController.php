<?php 

    namespace App\Controllers;

use App\Models\Cart;
use App\Models\Message;
use App\Models\OrderStatus;
use MF\Controller\Action;
use MF\Model\Container;

    class PaymentController extends Action
    {
        public function pagamento()
        {
            $this->restrict();

            if(!isset($_GET['idorder']) || $_GET['idorder'] < 1 || !isset($_GET['idpayment']) || $_GET['idpayment'] < 1){
                Message::setMessage('Dados incorretos ou invalidos', 'danger');
                exit;
            }
            $order = Container::getModel('order');
            $order->__set('idorder', $_GET['idorder']);
            $order->__set('idstatus', 2);
            $order->setStatus();
            
            $cart = Container::getModel('cart');

            $this->view->payment = $_GET['idpayment'];
            $this->view->title = "Pagamento";
            $this->resetCart();

            switch ($_GET['idpayment']) {
                case 1:
                    header('location: /pedido/boleto?idorder='.$_GET['idorder']);
                    exit;
                    break;
                case 2:
                    $this->view->order = $order->getOrder($_GET['idorder']);
                    $cart->__set('idCart', $this->view->order['idcart']);
                    $this->view->produtos = $cart->getAllProducts();
                    $this->render('pagseguro', 'nolayout');
                    exit;
                    break;
                case 3:
                    echo 'paypal';
                    header('location: /pedidos');
                    exit;
                    break;
                
                default:
                    echo 'erro';
                    exit;
                    break;
            }
                    
            
            


        }
        
        
        private function resetCart()
        {
            $cart = Cart::getFromSession();
            $cart->resetCart();
            $cart = Cart::getFromSession();
        }
        
        
    }


?>