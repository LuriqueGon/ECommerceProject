<?php

namespace App\Models;
use MF\Model\DAO;

    Class User extends DAO
    {
        protected $login;
        protected $password;
        protected $remember = false;

        public function getAll():array
        {
            return $this->selectAll('SELECT * FROM tb_users');
        }

        public function login ()
        {
            return $this->select('SELECT * FROM tb_users WHERE deslogin = ? AND despassword = ?', array($this->__get('login'),$this->__get('password')));
        }

    }


?>