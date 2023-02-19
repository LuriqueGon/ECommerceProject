<?php

namespace App\Models;
use MF\Model\DAO;

    Class User extends DAO
    {
        protected $id;
        protected $login;
        protected $password;
        protected $repassword;
        protected $nome;
        protected $email;
        protected $telefone;
        protected $inAdmin = 0;
        protected $idPerson;
        protected $perfil;
        protected $remember = false;


        public function getAll():array
        {
            return $this->selectAll('SELECT * FROM tb_users INNER JOIN tb_persons b USING(idperson) ORDER BY tb_users.dtregister DESC');
        }

        public function findById():array
        {
            return $this->select('SELECT * FROM tb_users INNER JOIN tb_persons b USING(idperson) WHERE iduser = ?', array($this->__get('id')));
        }

        public function findByEmail()
        {
            return $this->select("SELECT * FROM tb_persons WHERE desemail = ?", array($this->__get('email')));
        }

        public function findByLogin()
        {
            return $this->select("SELECT * FROM tb_users WHERE deslogin = ?", array($this->__get('login')));
        }

        public function findByTel()
        {
            return $this->select("SELECT * FROM tb_persons WHERE nrphone = ?", array($this->__get('telefone')));
        }

        
        

        public function getIdPerson():int
        {
            return $this->select('SELECT idperson FROM tb_users WHERE iduser = ?', array($this->__get('id')))['idperson'];
        }

        public function getIdPersonByEmail():int
        {
            return $this->select('SELECT idperson FROM tb_persons WHERE desemail = ?', array($this->__get('email')))['idperson'];
        }

        public function getIdPersonById():int
        {
            return $this->select('SELECT idperson FROM tb_users WHERE iduser = ?', array($this->__get('id')))['idperson'];
        }

        
        

        public function activeById():bool
        {
            return $this->rawQuery('UPDATE tb_users SET ativo = 1 WHERE iduser = ?', array($this->__get('id')));
        }

        public function desativeById():bool
        {
            return $this->rawQuery('UPDATE tb_users SET ativo = 0 WHERE iduser = ?', array($this->__get('id')));
        }

        public function deleteById():bool
        {
            $this->__set('idPerson', $this->getIdPersonById());
            $this->rawQuery('DELETE FROM tb_users WHERE idperson = ?', array(
                $this->__get('idPerson')
            ));
            return $this->rawQuery('DELETE FROM tb_persons WHERE idperson = ?', array(
                $this->__get('idPerson')
            ));
            
            

        }

        

        

        public function login ()
        {
            return $this->select('SELECT * FROM tb_users INNER JOIN tb_persons b USING(idperson) WHERE deslogin = ? AND despassword = ? AND ativo = 1', array($this->__get('login'),$this->__get('password')));
        }

        public function register():bool
        {
            if(!$this->findByEmail()){
                if(!$this->findByLogin()){
                    if(!$this->findByTel()){
                        $this->cadastrarPerson();
                        $this->__set('idPerson', $this->getIdPersonByEmail());
                        $this->cadastrarUser();

                        return true;
                        exit;

                    }

                }
            }

            return false;
        }

        private function cadastrarPerson():void
        {
            $query = "INSERT INTO tb_persons (desperson, desemail, nrphone) VALUES (?,?,?)";
            $params = array(
                $this->__get('nome'),
                $this->__get('email'),
                $this->__get('telefone')
            );
            $this->query($query, $params);

        }

        private function cadastrarUser():void
        {
            $query = "INSERT INTO tb_users (idperson, deslogin, despassword, inadmin) VALUES (?,?,?,?)";
            $params = array(
                $this->__get('idPerson'),
                $this->__get('login'),
                $this->__get('password'),
                $this->__get('inAdmin')
            );
            $this->query($query, $params);

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