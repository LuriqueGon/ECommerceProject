<?php

namespace App\Models;
use MF\Model\DAO;

    Class User extends DAO
    {
        public function getAll():array
        {
            return $this->selectAll('SELECT * FROM tb_users');
        }
    }


?>