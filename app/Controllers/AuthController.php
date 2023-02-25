<?php 

    namespace App\Controllers;

    use App\Models\Message;
    use MF\Controller\Action;
    use MF\Model\Container;
    use App\Models\Mailer;

    class AuthController extends Action
    {
        

        public function index()
        {
            $this->dontRestrict();
            $this->view->title = "Autenticação";
            $this->render('login');
            
        }

        public function forgotPasswordPages()
        {
            $this->dontRestrict();
            

            if(isset($_POST['email'])){ 
                if(empty($_POST['email'])){ 
                    Message::setMessage('Insira um email', 'danger', '/login/forgotPassword');
                    exit;

                }else{
                    $user = Container::getModel('user');
                    $user->__set('email', $_POST['email']);
                    if($user->forgot()){
                        Message::setMessage('Email de Redefinição de Senha Enviado', 'success', '/');
                        exit;
                    }else{
                        Message::setMessage('Email de Redefinição invalido', 'danger', 'back');
                        exit;
                    }
                    
                }
            }


            $this->view->title = "Esqueceu a sua Senha?";
            $this->render('forgotPassword');
            
        }

        public function forgotReset()
        {
            $this->dontRestrict();
            $this->needGET($_GET);

            $user = Container::getModel('user');
            $user->__set('idDecrypt', $user->decryptCode($_GET['code']));
            $results = $user->getIdByDecrypt();

            $id = !empty($results)
                   ? $results['iduser'] 
                   : Message::setMessage('Link de restauração de Senha invalido!! Tente novamente', 'danger');

            $user->__set('id', $id);
            
            $this->view->nome = $results['desperson'];
            $this->view->id = $user->__get('idDecrypt');
            $this->view->code = $_GET['code'];

            $this->view->title = "Redefinir a senha";
            $this->render('forgot-reset', 'noLayout');
        }

        public function resetPassword()
        {
            $this->dontRestrict();
            $this->needPOST($_POST);

            if(isset($_POST['password'])){
                if(empty($_POST['password'])) Message::setMessage('Informe uma senha valida', 'danger', 'back');

                $user = Container::getModel('user');
                $user->__set('idDecrypt', $user->decryptCode($_POST['code']));

                if($user->__get('idDecrypt') != $_POST['idRecovery']) Message::setMessage('O código de redefinição possui algum erro, tente novamente   ', 'danger', 'back');

                $results = $user->getIdByDecrypt();

                if(empty($results)) Message::setMessage('Ocorreu algum erro inesperado, tente novamente', 'danger', 'back');
                $user->__set('id', $results['iduser']);
                $user->__set('password', md5($_POST['password']));

                $user->recoveryPassword();

                Mailer::sendMail($results['desemail'], $results['desperson'],"Redefinição de Senha", 
                    "<h1>Redefinição em ECommerce Store Concluida com sucesso</h1><br><br><p style='color:green;font-size:1.4rem;'>Sua restauração de senha foi realizada com sucesso!!</p>"
                    
                )
                
                ;

                // Message::setMessage('Recuperação de senha realizada com sucesso', 'success');
            }
        }

        public function logout()
        {
            $this->restrict();
            $_SESSION = $this->unsetValueArray($_SESSION);
            Message::setMessage('Logout com sucesso', 'success', '/');
        }
       

        public function login()
        {
            $this->dontRestrict();
            $this->needPOST($_POST);

            $values = array(
                'login' => $_POST['login'],
                'password' => md5($_POST['password']),
                'remember' => isset($_POST['rememberme']) ? $_POST['rememberme'] : false
            );

            $user = Container::getModel('user');
            $user = $this->setValueObject($user, $values);
            $this->testLogin($user);
        }
       
        public function register()
        {

            $this->needPOST($_POST);
            $_POST['password'] = md5($_POST['password']);
            $_POST['repassword'] = md5($_POST['repassword']);
            $_POST['inAdmin'] = (isset($_POST['inAdmin']) && $_POST['inAdmin'] == 'on') ? 'true' : 'false';

            $user = Container::getModel('user');
            $user = $this->setValueObject($user, $_POST);

            if($user->__get('password') == $user->__get('repassword')){

                if($user->register()){

                    if(!isset($_SESSION['auth']) || !$_SESSION['auth']) $this->testLogin($user);
                    
                    else{
                        Message::setMessage('Conta Criada com sucesso!! <br> Por favor! Efetue login', 'success', 'back');
                        exit;
                    }

                }else Message::setMessage('Dados já existentes ou cadastrados', 'danger', 'back');

            }else Message::setMessage('Senhas não coincidem', 'danger', 'back');
        }

        private function testLogin($object)
        {
            $authLogin = $object->login();
            if(empty($authLogin)){
                Message::setMessage('Usuario e/ou Senha invalidos', 'danger', 'back');
                exit;
            }else{
                $authLogin['auth'] = true;
                $_SESSION = $this->setValueArray($_SESSION, $authLogin, array("despassword"));
                Message::setMessage('Logado', 'success', '/');
            }
        }

        
    }

?>