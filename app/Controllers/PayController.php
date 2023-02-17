<?php 

    namespace App\Controllers;
    use MF\Controller\Action;

    class PayController extends Action
    {
        public function index()
        {
            $this->view->title = "Pagamento";
            $this->render('pagamento');
        }
    }

?>