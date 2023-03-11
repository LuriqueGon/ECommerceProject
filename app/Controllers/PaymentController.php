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

            if(!isset($_GET['idorder']) || $_GET['idorder'] < 1){
                Message::setMessage('Dados incorretos ou invalidos', 'danger');
                exit;
            }

            $order = Container::getModel('order');
            $order->__set('idorder', $_GET['idorder']);
            $order->__set('idstatus', OrderStatus::AGUARDANDO_PAGAMENTO);
            $order->setStatus();

            $cart = Cart::getFromSession();
            $cart->resetCart();
            $cart = Cart::getFromSession();
            
            $this->view->title = "Pagamento";
            $this->render('payment'); 

        }
        
    }


?>