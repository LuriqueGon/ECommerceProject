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

            $this->view->title = "Criar Categoria";
            $this->render('categorieCreate', 'adminLayout');
        }

        public function loadUpdate()
        {
            $this->restrict();
            $this->inAdmin();
            $this->needGET($_GET);

            $categoria = Container::getModel('categorie');
            $categoria->__set('id', $_GET['id']);
            $this->view->categoria = $categoria->getById();

            $this->view->title = "Editar Categoria";
            $this->render('categorieEdit', 'adminLayout');
        }

        public function create()
        {
            $this->restrict();
            $this->inAdmin();
            $this->needPOST($_POST);

            if(empty($_POST['nome'])) Message::setMessage('Digite um nome válido', 'danger', 'back'); exit;

            $categoria = Container::getModel('Categorie');
            $categoria->__set('nome', $_POST['nome']);

            if($categoria->create()) {
                Message::setMessage('Categoria Criada com sucesso', 'success', 'back');
                exit;
            }
            else Message::setMessage('Categoria Já existente no banco', 'danger', 'back'); exit;
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

            $categoria->update();
            Message::setMessage('Editado com sucesso', 'success', '/admin/categorias');
            
        }

        
    }


?>