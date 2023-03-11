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
            $this->needGET($_GET);

            $user = Container::getModel('user');
            $user->__set('id', $_GET['id']);
            if($user->activeById()){
                Message::setMessage('ativado com sucesso', 'success', 'back');
            }
        }

        public function desativarUser()
        {
            $this->restrict();
            $this->inAdmin();
            $this->needGET($_GET);

            $user = Container::getModel('user');
            $user->__set('id', $_GET['id']);
            if($user->desativeById()){
                Message::setMessage('desativado com sucesso', 'success', 'back');
            }
        }

        public function deletarUser()
        {
            $this->restrict();
            $this->inAdmin();
            $this->needGET($_GET);

            $user = Container::getModel('user');
            $user->__set('id', $_GET['id']);
            if($user->deleteById()){
                Message::setMessage('Deletado com sucesso', 'success', 'back');
            }
        }
        
        public function salvarEdition()
        {
            $this->restrict();
            $this->inAdmin();

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
        
        public function profile()
        {
            $this->restrict();
            

            $this->view->title = 'Minha Conta';
            $this->render('profile');
        }

        public function profileUpdate()
        {
            $this->restrict();
            $this->needPOST($_POST);

            $params = array(
                'nome'=> $_POST['desperson'],
                'email'=> $_POST['desemail'],
                'telefone'=> $_POST['nrphone'],
                'id' => $_SESSION['User']['iduser']
            );

            $user = Container::getModel('user');
            $user = $this->setValueObject($user, $params);
            $user->__set('idPerson', $user->getIdPersonById());
            $user->__set('id', $user->getIdByIdPerson());
            var_dump($user);

            $user->edit();

            $result = $user->findById();
            $_SESSION['User'] = $this->setValueArray($_SESSION['User'],$result);

            Message::setMessage('Alterações feitas com sucesso', 'success', '/profile');
        }

        public function profileChangePhoto()
        {
            $this->restrict();
            $this->needPOST($_POST);

            $user = Container::getModel('user');
            $user->__set('idPerson', $_POST['personId']);


            $file = $_FILES['photo'];
            $userName = str_replace(' ', '-',$_SESSION['User']['desperson']);
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

            $user->savePhoto();

            $_SESSION['User']['perfil'] = $user->__get('perfil');

            Message::setMessage('foto alterada com sucesso', 'success', 'back');
        }

        public function changePass()
        {
            $this->restrict();

            $this->view->title = "Alterar Senha";
            $this->render('changePass');
        }

        public function updatePassword()
        {
            $this->restrict();
            $this->needPOST($_POST);

            $user = Container::getModel('user');

            $user->__set('oldPass', md5($_POST['current_pass']));
            $user->__set('password', md5($_POST['new_pass']));
            $user->__set('repassword', md5($_POST['new_pass_confirm']));
            $user->__set('id', $_SESSION['User']['iduser']);

            if($user->__get('password') != $user->__get('repassword')){
                Message::setMessage('As senhas não coincidem', 'danger', 'back');
                exit;
            } 

            if(empty($user->testPassword()))
            {
                Message::setMessage('A senha está incorreta', 'danger', 'back');
                exit;
            } 

            if(strlen($_POST['new_pass']) < 8)
            {
                Message::setMessage('A senha deve conter 8 caracteres', 'danger', 'back');
                exit;
            } 

            $user->updatePassword();
            Message::setMessage('Senha Alterada com sucesso', 'success', '/profile');



            var_dump($user);
        }
        
        
    }
