<?php 

    namespace App\Controllers;
    use MF\Controller\Action;
    use MF\Model\Container;
    use App\Models\Message;

    class CategorieController extends Action
    {   
        public function index()
        {
            $this->restrict();
            $this->inAdmin();

            $categoria = Container::getModel('categorie');

            $this->view->title = "Categorias";
            $this->view->categories = $categoria->getAll();
            $this->render('allCategories', 'adminTable');
        }

        public function loadCreate()
        {
            $this->restrict();
            $this->inAdmin();

            $this->view->categoria = array(
                'descategory' => "",
                'idcategory' => ""
            );

            $this->view->pageTitle = 'Cadastrar';
            $this->view->url = '/admin/categoria/create';
            $this->view->action = '/admin/categoria/criar';

            $this->view->title = "Criar Categoria";
            $this->render('categoriesUpdate', 'adminLayout');
        }

        public function loadUpdate()
        {
            $this->restrict();
            $this->inAdmin();
            $this->needGET($_GET);

            $categoria = Container::getModel('categorie');
            $categoria->__set('id', $_GET['id']);
            $this->view->categoria = $categoria->getById();

            $this->view->pageTitle = 'Editar';
            $this->view->url = '#';
            $this->view->action = '/admin/categoria/editar';

            $this->view->title = "Editar Categoria";
            $this->render('categoriesUpdate', 'adminLayout');
        }

        public function loadProdutos()
        {
            $this->restrict();
            $this->inAdmin();
            $this->needGET($_GET);

            $categoria = Container::getModel('categorie');
            $categoria->__set('id', $_GET['id']);

            $produto = Container::getModel('product');
            $produto->__set('cateId', $categoria->__get('id'));

            $this->view->categoria = $categoria->getById();
            $this->view->produtosCategoria = $produto->getAllCategoria();
            $this->view->produtosDontCategoria = $produto->getAllDontCategoria();

            $this->view->title = "Produtos da Categoria ".$this->view->categoria['descategory'];
            $this->render('categoriesProducts', 'adminTable');
        }



        public function create()
        {
            $this->restrict();
            $this->inAdmin();
            $this->needPOST($_POST);
            
            var_dump($_POST['nome']);
            $_POST['nome'] = empty($_POST['nome']) ?
                             Message::setMessage('Digite um nome v??lido', 'danger', 'back'):
                             $_POST['nome'];
            
            echo 1;
            $categoria = Container::getModel('Categorie');
            $categoria->__set('nome', $_POST['nome']);
            echo 1;
            if($categoria->create()) {
                Message::setMessage('Categoria Criada com sucesso', 'success', 'back');
                exit;
            }
            else Message::setMessage('Categoria J?? existente no banco', 'danger', 'back'); exit;
        }

        public function delete()
        {
            $this->restrict();
            $this->inAdmin();
            $this->needGET($_GET);

            $categoria = Container::getModel('categorie');
            $categoria->__set('id', $_GET['id']);
            if($categoria->delete()){
                Message::setMessage('Deletado com sucesso', 'success', 'back');
            }
        }
        public function update()
        {
            $this->restrict();
            $this->inAdmin();
            $this->needPOST($_POST);

            $categoria = Container::getModel('categorie');
            $categoria->__set('id', $_POST['id']);
            $categoria->__set('nome', $_POST['nome']);

            if($categoria->update()){
                Message::setMessage('Editado com sucesso', 'success', '/admin/categorias');

            }else{
                Message::setMessage('Categoria J?? existente no banco', 'danger', 'back');
            }
            
        }

        public function addProdInCate()
        {
            $this->restrict();
            $this->inAdmin();

            if(!isset($_GET['idCate'])|| empty($_GET['idCate'])) Message::setMessage('Informe os valores para adicionar os produtos', 'danger', 'back');
            if(!isset($_GET['idProd'])|| empty($_GET['idProd'])) Message::setMessage('Informe os valores para adicionar os produtos', 'danger', 'back');

            $produto = Container::getModel('product');
            $produto->__set('cateId', $_GET['idCate']);
            $produto->__set('id', $_GET['idProd']);
            $produto->addProdInCate();

            Message::setMessage('Adicionado com Sucesso', 'success', 'back');
        }

        public function removeProdInCate()
        {
            $this->restrict();
            $this->inAdmin();

            if(!isset($_GET['idCate'])|| empty($_GET['idCate'])) Message::setMessage('Informe os valores para adicionar os produtos', 'danger', 'back');
            if(!isset($_GET['idProd'])|| empty($_GET['idProd'])) Message::setMessage('Informe os valores para adicionar os produtos', 'danger', 'back');

            $produto = Container::getModel('product');
            $produto->__set('cateId', $_GET['idCate']);
            $produto->__set('id', $_GET['idProd']);
            $produto->removeProdInCate();

            Message::setMessage('Removido com Sucesso', 'success', 'back');
        }

        

        
    }


?>