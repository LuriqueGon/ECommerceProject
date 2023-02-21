<?php

namespace App\Models;
use MF\Model\DAO;

    Class Categorie extends DAO
    {
        protected $id;
        protected $nome;
        protected $registro;

        public function getAll():array
        {
            return $this->selectAll("SELECT * FROM tb_categories ORDER BY dtregister DESC");
        }

        public function getById():array
        {
            return $this->select("SELECT * FROM tb_categories WHERE idcategory = ?", array($this->__get('id')));
        }

        public function create():bool
        {
            if($this->read())return false;

            $this->query('INSERT INTO tb_categories( descategory ) VALUES (?)', array($this->__get('nome')));
            return true;
        }

        public function read()
        {
            return $this->select('SELECT * FROM tb_categories WHERE descategory = ?', array($this->__get('nome')));
        }

        public function update()
        {
            if($this->read())return false;

            $this->query('UPDATE tb_categories SET descategory = ? WHERE idcategory = ?', array($this->__get('nome'), $this->__get('id')));

            return true;
        }

        public function delete():bool
        {
            $this->query('DELETE FROM tb_categories WHERE idcategory = ?', array($this->__get('id')));
            return true;
        }
        
        
    }


?>