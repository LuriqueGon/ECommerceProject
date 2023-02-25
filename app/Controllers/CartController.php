<?php 

    namespace App\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Message;
use MF\Controller\Action;
use MF\Model\Container;

    class CartController extends Action
    {
        public function index()
        {

            $cart = Container::getModel('cart');
            
            if(isset($_SESSION[Cart::SESSION]) && !empty($_SESSION[Cart::SESSION])){
                
                $cart->__set('idCart', $_SESSION[Cart::SESSION]['idCart']);
                $cartValues = $cart->getCart();
                
                $this->view->productsCart = $cart->getAllProducts();

                // var_dump($this->view->productsCart);

            }else{

                $data = array(
                    'idSession' => session_id(),
                    'idCart' => isset($cart->getFromSessionId()['idcart'])?$cart->getFromSessionId()['idcart'] :"",
                );

                if(User::checkLogin())$data['idUser'] = $_SESSION['iduser'];

                $cart = $this->setValueObject($cart, $data);
                $cart->save();
                $cart->setToSession();
                $this->view->productsCart = array();
                var_dump($_SESSION);
                var_dump($cart);
            }
            
            $this->view->title = "Carrinho";
            $this->render('cart');
            
        }

        public function addProduct()
        {
            if( !isset($_GET['idProduct']) || empty($_GET['idProduct'])) Message::setMessage('Informe o id do produto', 'danger','/cart');

            $produto = Container::getModel('product');
            $produto->__set('id', $_GET['idProduct']);
            
            $cart = Container::getModel('cart');
            $cart->__set('idCart', $_SESSION['Cart']['idCart']);
            $cart->addProduct($produto);    
            
            Message::setMessage('Produto Adicionado com sucesso', 'success', '/cart');
        }

        public function removeProduct()
        {
            if( !isset($_GET['idProduct']) || empty($_GET['idProduct'])) Message::setMessage('Informe o id do produto', 'danger','/cart');
            if( !isset($_GET['all']) || empty($_GET['all'])) Message::setMessage('Informe a quantidade de produtos', 'danger','/cart');

            $produto = Container::getModel('product');
            $produto->__set('id', $_GET['idProduct']);
            
            $cart = Container::getModel('cart');
            $cart->__set('idCart', $_SESSION['Cart']['idCart']);

            $_GET['all'] = ($_GET['all'] === "false") ? false : true;

            $cart->removeProduct($produto, $_GET['all']);    
            
            Message::setMessage('Produto Removido com sucesso', 'success', '/cart');
        }

        
    }

?>