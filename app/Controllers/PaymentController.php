<?php 

    namespace App\Controllers;
    use MF\Controller\Action;

    class PayController extends Action
    {
        public function pagamento()
        {
            $this->restrict();
            $this->view->title = "Pagamento";
            $this->render('payment');
        }
    }

?>