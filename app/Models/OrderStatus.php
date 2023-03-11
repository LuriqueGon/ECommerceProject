<?php

namespace App\Models;
use MF\Model\DAO;

    Class OrderStatus extends DAO
    {
        const EM_ABERTO = 1;
        const AGUARDANDO_PAGAMENTO = 2;
        const PAGO = 3;
        const ENTREGUE = 4;
        
        public function listAll()
        {
            return $this->selectAll("SELECT * FROM tb_ordersstatus ORDER BY desstatus");
        }
    }


?>