<?php 

    namespace App\Controllers;

use App\Models\Message;
use MF\Controller\Action;
use MF\Model\Container;

    class ComentController extends Action
    {
        public function setComent()
        {
            $this->restrict();
            $this->needPOST($_POST);

            if(empty($_POST['rating'])) Message::setMessage('Informe a nota clicando nas estrelas', 'danger', 'back');

            $params = array(
                'idUser'=>$_POST['idUser'],
                'idProd'=>$_POST['idProduct'],
                'subject'=>$_POST['subject'],
                'coment'=>$_POST['review'],
                'rating'=>$_POST['rating']
            );

            var_dump($_POST);

            $coment = Container::getModel('coment');
            $coment = $this->setValueObject($coment, $params);
            $coment->setComent();

            Message::setMessage('Comentario feito com sucesso', 'success', 'back','#coments');

            
        }

        public function like()
        {
            $this->restrict();

            $coment = Container::getModel('coment');
            $coment = $coment->__set('id', $_GET['idComent']);
            $coment = $coment->__set('idUser', $_GET['iduser']);
            $coment->like();

            Message::setMessage('Você deu Like', 'success','back','#coments');
        }

        public function unlike()
        {
            $this->restrict();

            $coment = Container::getModel('coment');
            $coment = $coment->__set('id', $_GET['idComent']);
            $coment = $coment->__set('idUser', $_GET['iduser']);
            $coment->unlike();

            Message::setMessage('Você deu um Unlike', 'success','back','#coments');
        }

        

        public function desfazer()
        {
            $this->restrict();

            $coment = Container::getModel('coment');
            $coment = $coment->__set('id', $_GET['idComent']);
            $coment = $coment->__set('idUser', $_GET['iduser']);
            $coment->desfazer();

            Message::setMessage('Feedback removido', 'success','back','#coments');

            var_dump($coment);
        }
        
    }

?>