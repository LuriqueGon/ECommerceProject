<?php

namespace App\Models;
use MF\Model\DAO;

    Class Categorie extends DAO
    {
        protected $id;
        protected $nome;
        protected $registro;
        protected $idProd;

        public function getAll(Int $order = 0):array
        {
            if($order === 0)return $this->selectAll("SELECT * FROM tb_categories ORDER BY dtregister DESC");
            else return $this->selectAll("SELECT * FROM tb_categories ORDER BY descategory ASC");
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

        public function getAllCategoria():array
        {
            return $this->selectAll(
                'SELECT * FROM tb_categories WHERE idcategory IN (
                    SELECT a.idcategory FROM tb_categories a INNER JOIN tb_productscategories b 
                    ON a.idcategory = b.idcategory WHERE b.idproduct = ?)', array($this->__get('idProd'))
                );
        }

        public function getAllDontCategoria():array
        {
            return $this->selectAll(
                'SELECT * FROM tb_categories WHERE idcategory NOT IN (
                    SELECT a.idcategory FROM tb_categories a INNER JOIN tb_productscategories b 
                    ON a.idcategory = b.idcategory WHERE b.idproduct = ?)', array($this->__get('idProd')));
        }
        
        public function addCateInProd()
        {
            $this->query('INSERT INTO `tb_productscategories`(idcategory, idproduct) VALUES (?,?)', array(
                $this->__get('id'),
                $this->__get('idProd'),
            ));
        }

        public function removeCateInProd()
        {
            $this->query('DELETE FROM `tb_productscategories` WHERE idcategory = ? AND idproduct = ?', array(
                $this->__get('id'),
                $this->__get('idProd'),
            ));
        }

        
        
    }


?>