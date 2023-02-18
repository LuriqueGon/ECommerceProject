<?php

namespace App\Models;
use MF\Model\DAO;

    Class User extends DAO
    {
        protected $id;
        protected $login;
        protected $password;
        protected $nome;
        protected $email;
        protected $telefone;
        protected $inAdmin;
        protected $idPerson;
        protected $perfil;
        protected $remember = false;


        public function getAll():array
        {
            return $this->selectAll('SELECT * FROM tb_users INNER JOIN tb_persons b USING(idperson) ORDER BY desperson');
        }

        public function findById():array
        {
            return $this->select('SELECT * FROM tb_users INNER JOIN tb_persons b USING(idperson) WHERE iduser = ?', array($this->__get('id')));
        }

        public function getIdPerson():int
        {
            return $this->select('SELECT idperson FROM tb_users WHERE iduser = ?', array($this->__get('id')))['idperson'];
        }

        public function activeById():bool
        {
            return $this->rawQuery('UPDATE tb_users SET ativo = 1 WHERE iduser = ?', array($this->__get('id')));
        }

        public function deleteById():bool
        {
            return $this->rawQuery('UPDATE tb_users SET ativo = 0 WHERE iduser = ?', array($this->__get('id')));
        }

        

        public function login ()
        {
            return $this->select('SELECT * FROM tb_users INNER JOIN tb_persons b USING(idperson) WHERE deslogin = ? AND despassword = ? AND ativo = 1', array($this->__get('login'),$this->__get('password')));
        }

        public function edit()
        {
            if(!empty($this->__get('password'))){
                $this->query("UPDATE tb_users SET despassword = ? WHERE iduser = ?", array(md5($this->__get('password')), $this->__get('id')));
            }

            if(!empty($this->__get('login'))){
                $this->query("UPDATE tb_users SET deslogin = ? WHERE iduser = ?",array($this->__get('login'),$this->__get('id')));
            }
            
            if($this->__get('inAdmin') == "on"){
                $this->query("UPDATE tb_users SET inAdmin = 1 WHERE iduser = ?", array($this->__get('id')));
            }else{
                $this->query("UPDATE tb_users SET inAdmin = 0 WHERE iduser = ?", array($this->__get('id')));
            }

            if(!empty($this->__get('nome'))){
                $this->query("UPDATE tb_persons SET desperson = ? WHERE idperson = ?",array($this->__get('nome'),$this->__get('idPerson')));
            }

            if(!empty($this->__get('email'))){
                $this->query("UPDATE tb_persons SET desemail = ? WHERE idperson = ?",array($this->__get('email'),$this->__get('idPerson')));
            }

            if(!empty($this->__get('telefone'))){
                $this->query("UPDATE tb_persons SET nrphone = ? WHERE idperson = ?",array($this->__get('telefone'),$this->__get('idPerson')));
            }

            if(!empty($this->__get('perfil'))){
                $this->query("UPDATE tb_persons SET perfil = ? WHERE idperson = ?",array($this->__get('perfil'),$this->__get('idPerson')));
            }

            
            
            
        }

    }


?>