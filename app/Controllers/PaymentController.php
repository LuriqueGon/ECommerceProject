<?php 

    namespace App\Controllers;

use App\Models\Message;
use MF\Controller\Action;

    class PaymentController extends Action
    {
        public function pagamento()
        {
            $this->restrict();

            if(!isset($_GET['idorder']) || $_GET['idorder'] < 1)
            {
                Message::setMessage('Dados incorretos ou invalidos', 'danger');
            }
            
            $this->view->total = isset($_SESSION['Cart']['total']) ? $_SESSION['Cart']['total'] : "0";
            $this->view->quantity = isset($_SESSION['Cart']['quantity']) ? $_SESSION['Cart']['quantity'] : "0";
            
            $this->view->title = "Pagamento";
            $this->render('payment'); 
        }
    }

?>