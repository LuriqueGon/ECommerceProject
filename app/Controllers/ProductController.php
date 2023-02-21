<?php 

    namespace App\Controllers;
    use MF\Controller\Action;
    use MF\Model\Container;

    class ProductController extends Action
    {
        public function index()
        {

            if(isset($_GET['categoria']) && !empty($_GET['categoria'])){
                $categoria = Container::getModel('categorie');
                $categoria->__set('id', $_GET['categoria']);
                $result = $categoria->getById();
                $this->view->title = "Categoria ". $result['descategory'];
                $this->view->category = $result['descategory'];
                $this->render('produtos');
            }else{
                $this->view->title = "Listagem de Produtos";
                $this->render('produtos');
            }

            
        }

        public function details()
        {
            $this->view->title = "Detalhes dos Produtos";
            $this->render('productsDetails');
        }
    }

?>