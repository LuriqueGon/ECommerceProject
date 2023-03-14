<?php

namespace App\Models;
use MF\Model\DAO;

    Class Payment extends DAO
    {
        public function getAll()
        {
            return $this->selectAll('SELECT * FROM tb_orderspayment');
        }
    }


?>