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
                'password' => md5($_POST['senha']),
                'remember' => isset($_POST['rememberme']) ? $_POST['rememberme'] : false
            );

            $user = Container::getModel('user');
            $user = $this->setValueObject($user, $values);
            $authLogin = $user->login();
            
            if(empty($authLogin)){
                Message::setMessage('Usuario e/ou Senha invalidos', 'danger');
                exit;
            }else{
                $authLogin['auth'] = true;
                $_SESSION = $this->setValueArray($_SESSION, $authLogin, array("despassword"));
                Message::setMessage('Logado', 'success', '/');
            }



            

            
        }

        

       
        public function register()
        {

            $this->dontRestrict();
            $this->needPOST($_POST);
        }

        
    }

?>