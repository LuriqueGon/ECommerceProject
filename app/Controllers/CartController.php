<?php 

    namespace App\Controllers;

    use App\Models\Cart;
    use App\Models\Message;
    use MF\Controller\Action;
    use MF\Model\Container;

    class CartController extends Action
    {
        public function index()
        {
            $cart = Cart::getFromSession();

            $this->view->cart = $cart;
            
            $this->view->productsCart = $cart->getAllProducts();

            $this->view->title = "Carrinho";
            $this->render('cart');
            
        }

        public function addProduct()
        {
            if( !isset($_GET['idProduct']) || empty($_GET['idProduct'])) Message::setMessage('Informe o id do produto', 'danger','/cart');

            $produto = Container::getModel('product');
            $produto->__set('id', $_GET['idProduct']);
            
            $cart = Cart::getFromSession();

            if(!empty($_GET['quantity']) && $_GET['quantity'] != 1){
                for($i = 1; $i<=$_GET['quantity']; $i++){
                    $cart->addProduct($produto);  
                }
            }else{
                $cart->addProduct($produto);  
            }
            
            Message::setMessage('Produto Adicionado com sucesso', 'success', '/cart');
        }

        public function removeProduct()
        {
            if( !isset($_GET['idProduct']) || empty($_GET['idProduct'])) Message::setMessage('Informe o id do produto', 'danger','/cart');
            if( !isset($_GET['all']) || empty($_GET['all'])) Message::setMessage('Informe a quantidade de produtos', 'danger','/cart');

            $produto = Container::getModel('product');
            $produto->__set('id', $_GET['idProduct']);
            
            $cart = Cart::getFromSession();

            $_GET['all'] = ($_GET['all'] === "false") ? false : true;

            $cart->removeProduct($produto, $_GET['all']);  
            
            Message::setMessage('Produto Removido com sucesso', 'success', '/cart');
        }

        public function calcularFrete()
        {
            $this->needPOST($_POST);

            if(strlen($_POST['cep']) < 8)
            {
                Message::setMessage('Insira um cep válido', 'danger', 'back');
                exit;
            }

            $cart = Cart::getFromSession();
            $cart->__set('zipCode', $_POST['cep']);
            $result = $cart->setFreight();

            $address = Container::getModel('address');
            $_POST['deszipcode'] = $_POST['cep']; 
            $_POST['idPerson'] = $_SESSION['User']['idperson'];

            unset($_POST['cep']);
            
            $address = $this->setValueObject($address, $_POST);
            $address->__set('id', $address->getIdByPerson());
            $values = $address->loadFromCep($address->__get('deszipcode'));
            $address= $this->setValueObject($address, $values);
            
            $address->save();
            
            Message::setMessage('Frete calculado com sucesso', 'success', isset($_GET['redirect'])? '/'.$_GET['redirect']:'/cart');
        }

        
        
    }