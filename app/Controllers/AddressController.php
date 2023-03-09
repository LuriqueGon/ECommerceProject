<?php 

    namespace App\Controllers;

    use App\Models\Cart;
    use App\Models\Message;
    use MF\Controller\Action;
    use MF\Model\Container;

    class AddressController extends Action
    {
        public function checkout()
        {
            $this->restrict('/checkout');

            $cart = Cart::getFromSession();
            $address = Container::getModel('address');

            if(empty($cart->getAllProducts())) {
                Message::setMessage('Insira produtos no carrinho para acessar essa página', 'danger', '/cart');
                exit;
            }

            $address->__set('idPerson', $_SESSION['idperson']);
            $this->view->address = $address->getAddress();

            $this->view->cart = $cart;
            $this->view->productsCart = $cart->getAllProducts();

            $cep = (isset($_SESSION['Cart']['zipCode'])) ? $_SESSION['Cart']['zipCode'] : "";


            if(empty($this->view->address['deszipcode']))
            {
                if(!empty($cep))
                {
                    $this->view->address = $address->loadFromCep($cep);;
                    $address = $this->setValueObject($address, $this->view->address);
                    $address = $address->save();
                    header('location: /checkout');
                    var_dump($address);
                }
            }

            if(!empty($this->view->address['deszipcode']))
            {
                $this->view->address['deszipcode'] = str_replace('-','',$this->view->address['deszipcode']);
                $cart->__set('zipCode', $this->view->address['deszipcode']);
                $cart->save();
                $_SESSION['Cart']['zipCode'] = $this->view->address['deszipcode'];
                $cart->setFreight();
            }

            if(isset($_GET['complement']) || !empty($_GET['complement'])) {
                echo 1;
                $this->view->address['descomplement'] = $_GET['complement'];
            }
            if(isset($_GET['number']) || !empty($_GET['number'])) {
                $this->view->address['desnumber'] = $_GET['number'];
            }

            if(isset($_SESSION['Cart']['zipCode']) && empty($_SESSION['Cart']['freight']))
            {
                $cart->__set('zipCode', $_SESSION['Cart']['zipCode']);
                $cart->setFreight();
            }

            var_dump($this->view->address);

            $this->view->title = 'Pagamento';
            $this->render('checkout');
        }


        public function checkoutInfo()
        {
            $this->needPOST($_POST);
            $this->validCheckout($_POST);
            $this->saveAddress($_POST);

            echo 1;
        }

        private function saveAddress($POST)
        {
            $_POST = $POST;

            $zipcode = substr($_POST['cep'],0,5);
            $zipcode .= "-".substr($_POST['cep'],5,3);
            $_POST['cep'] = $zipcode;

            $address = Container::getModel('address');
            $address->__set('idPerson', $_SESSION['idperson']);
            $_POST['deszipcode'] = $_POST['cep'];
            unset($_POST['cep']);
            $address = $this->setValueObject($address, $_POST);
            $address->__set('id',$address->getIdByPersonAndCEP());

            $address->save();

            var_dump($address);
        }

        private function validCheckout($POST):void
        {
            $_POST = $POST;

            $returnValues = !empty($_POST['descomplement']) ? "?complement=".$_POST['descomplement'] : "";

            $returnValues .= !empty($_POST['desnumber']) ? "&number=".$_POST['desnumber'] : "";

            if(!isset($_POST['cep']) || empty($_POST['cep']))
            {
                Message::setMessage('Insira os dados corretos do campo de CEP ', 'danger','/checkout', $returnValues);
                exit;
            }

            if(!isset($_POST['cep']) || empty($_POST['cep']))
            {
                Message::setMessage('Insira os dados corretos do campo de CEP ', 'danger','/checkout', $returnValues);
                exit;
            }

            if(!isset($_POST['desaddress']) || empty($_POST['desaddress']))
            {
                Message::setMessage('Insira os dados corretos do campo de ENDEREÇO ', 'danger','/checkout', $returnValues);
                exit;
            }

            if(!isset($_POST['desdistrict']) || empty($_POST['desdistrict']))
            {
                Message::setMessage('Insira os dados corretos do campo de BAIRRO ', 'danger','/checkout', $returnValues);
                exit;
            }

            if(!isset($_POST['descity']) || empty($_POST['descity']))
            {
                Message::setMessage('Insira os dados corretos do campo de CIDADE ', 'danger','/checkout', $returnValues);
                exit;
            }

            if(!isset($_POST['desstate']) || empty($_POST['desstate']))
            {
                Message::setMessage('Insira os dados corretos do campo de ESTADO ', 'danger','/checkout', $returnValues);
                exit;
            }

            if(!isset($_POST['desnumber']) || empty($_POST['desnumber']))
            {
                Message::setMessage('Insira os dados corretos do campo de NÚMERO ', 'danger','/checkout', $returnValues);
                exit;
            }

            if(!isset($_POST['descountry']) || empty($_POST['descountry']))
            {
                Message::setMessage('Insira os dados corretos do campo de PAÍS ', 'danger','/checkout', $returnValues);
                exit;
            }
        }
        
    }

?>