<?php 

    namespace App\Controllers;

use App\Models\Message;
use MF\Controller\Action;
use MF\Model\Container;

    class UserController extends Action
    {
        public function allUsers()
        {
            $this->restrict();
            $this->inAdmin();

            $user = Container::getModel('user');
            $this->view->users = $user->getAll();
            
            $this->view->title = "Todos os Usuarios";
            $this->render('allUsers', 'adminTable');
        }

        public function cadastrarUser()
        {
            $this->restrict();
            $this->inAdmin();

            
            $this->view->title = "Cadastrar Usuarios";
            $this->render('createUser', 'adminLayout');
        }

        public function editarUser()
        {
            $this->restrict();
            $this->inAdmin();
            $this->needGET($_GET);

            $user = Container::getModel('user');
            $user->__set('id', $_GET['id']);
            $this->view->user = $user->findById();

            $this->view->title = "Editar Usuario";
            $this->render('editUser', 'adminLayout');
        }


        public function ativarUser()
        {
            $this->restrict();
            $this->inAdmin();

            $user = Container::getModel('user');
            $user->__set('id', $_GET['id']);
            if($user->activeById()){
                Message::setMessage('ativado com sucesso', 'success', 'back');
            }
        }

        public function deletarUser()
        {
            $this->restrict();
            $this->inAdmin();

            $user = Container::getModel('user');
            $user->__set('id', $_GET['id']);
            if($user->deleteById()){
                Message::setMessage('ativado com sucesso', 'success', 'back');
            }
        }

        public function salvarEdition()
        {
            $user = Container::getModel('user');
            $user = $this->setValueObject($user, $_POST);
            $user->__set('idPerson', $user->getIdPerson());

            

            $file = $_FILES['perfil'];
            $userName = str_replace(' ', '-',$_POST['nome']);
            $path = "./img/perfil";
            $fileName = bin2hex(random_bytes(20)). '.jpg';
            $perfil = $path."/$userName/".$fileName;
            

            if(!empty($file)){
                $file['type'] = explode('/',$file['type'])[1];
                if(in_array($file['type'], ["jpeg","jpg","JPEG","JPG", "png", "PNG"])){

                    if($file['error'])Message::setMessage($file['error'], 'danger', 'back');

                    if(!is_dir($path))mkdir($path);

                    if(!is_dir($path.'/'.$userName ))mkdir($path.'/'. $userName);

                    if(move_uploaded_file($file['tmp_name'], $perfil)){

                        $user->__set('perfil', "$userName/".$fileName);

                    }else Message::setMessage("Uploud do arquivo falhou", 'danger', 'back');

                }else Message::setMessage("Tipo do arquivo invalido, só aceitamos JPG e PNG", 'danger', 'back');

            }

            $user->edit();
            Message::setMessage('Edição realizada com sucesso', 'success', '/admin/users');


            echo "<pre>";
            var_dump($user);
            echo "</pre>";
        }
        
        
        
    }
