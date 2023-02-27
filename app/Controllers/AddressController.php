<?php 

    namespace App\Controllers;

use App\Models\Cart;
use App\Models\Message;
    use MF\Controller\Action;
    use MF\Model\Container;
    use App\Models\Mailer;

    class AddressController extends Action
    {
        public function checkout()
        {
            $this->restrict('/checkout');

            $cart = Cart::getFromSession();
            $address = Container::getModel('address');
            $address->__set('idPerson', 1);
            var_dump($cart);
            $this->view->cart = $cart;
            $this->view->address = $address->getAddress();
            $this->view->title = 'Pagamento';
            $this->render('checkout');
        }

        
    }

?>