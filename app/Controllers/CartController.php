<?php 

    namespace App\Controllers;

    use App\Models\Message;
    use MF\Controller\Action;
    use MF\Model\Container;

    class CartController extends Action
    {
        public function index()
        {
            $cart = Container::getModel('cart');
            $cart = $cart->getFromSession();
            var_dump($_SESSION['Cart']);
            
            $this->view->productsCart = $cart->getAllProducts();

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
            
            $cart = Container::getModel('cart');
            $cart->__set('idCart', $_SESSION['Cart']['idCart']);

            $_GET['all'] = ($_GET['all'] === "false") ? false : true;

            $cart->removeProduct($produto, $_GET['all']);    
            
            Message::setMessage('Produto Removido com sucesso', 'success', '/cart');
        }
        
    }