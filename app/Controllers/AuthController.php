<?php 

    namespace App\Controllers;

    use App\Models\Message;
    use MF\Controller\Action;
    use MF\Model\Container;

    class AuthController extends Action
    {

        public function index()
        {
            $this->dontRestrict();
            $this->view->title = "Autenticação";
            $this->render('login');
            
        }

        public function forgetPasswordPages()
        {
            $this->dontRestrict();
            $this->view->title = "Esqueceu a sua Senha?";
            $this->render('forgetPassword');
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
                        Message::setMessage('Conta Criada com sucesso!! <br> Por favor! Efetue login', 'success', '/admin/users');
                        exit;
                    }

                }else Message::setMessage('Dados já existentes ou cadastrados', 'danger', 'back');

            }else Message::setMessage('Senhas não coincidem', 'danger', 'back');
        }

        private function testLogin($object)
        {
            $authLogin = $object->login();
            if(empty($authLogin)){
                Message::setMessage('Usuario e/ou Senha invalidos', 'danger');
                exit;
            }else{
                $authLogin['auth'] = true;
                $_SESSION = $this->setValueArray($_SESSION, $authLogin, array("despassword"));
                Message::setMessage('Logado', 'success', '/');
            }
        }

        
    }

?>