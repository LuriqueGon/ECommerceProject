<?php

namespace App\Models;
use MF\Model\DAO;

    Class Product extends DAO
    {
        protected $id;
        protected $nome;
        protected $preco;
        protected $peso;
        protected $largura;
        protected $altura;
        protected $comprimento;
        protected $descricao;
        protected $url;
        protected $photo;

        public function getAll():array
        {
            return $this->selectAll('SELECT * FROM tb_products ORDER BY dtregister DESC');
        }

        public function findById():array
        {
            return $this->select('SELECT * FROM tb_products WHERE idproduct = ?', array($this->__get('id')));
        }

        public function findByUrl():array
        {
            return $this->select('SELECT * FROM tb_products WHERE desurl = ?', array($this->__get('url')));
        }

        
        public function create():void
        {
            $this->query('INSERT INTO tb_products( desproduct, vlprice, vlwidth, vlheight, vllength, vlweight, desurl, descricao ) VALUES (?,?,?,?,?,?,?,?)', array(
                $this->__get('nome'),
                $this->__get('preco'),
                $this->__get('largura'),
                $this->__get('altura'),
                $this->__get('comprimento'),
                $this->__get('peso'),
                $this->__get('url'),
                $this->__get('descricao')
            ));
        }

        public function read()
        {
            return $this->select('SELECT * FROM tb_products WHERE idproduct = ?', array($this->__get('idd')));
        }

        public function update():void
        {
            if(!empty($this->__get('nome'))){
                $this->query("UPDATE tb_products SET desproduct = ? WHERE idproduct = ?", array(
                    $this->__get('nome'),
                    $this->__get('id')
                ));
            }
            if(!empty($this->__get('preco'))){
                $this->query("UPDATE tb_products SET vlprice = ? WHERE idproduct = ?", array(
                    $this->__get('preco'),
                    $this->__get('id')
                ));
            }
            if(!empty($this->__get('largura'))){
                $this->query("UPDATE tb_products SET vlwidth = ? WHERE idproduct = ?", array(
                    $this->__get('largura'),
                    $this->__get('id')
                ));
            }
            if(!empty($this->__get('altura'))){
                $this->query("UPDATE tb_products SET vlheight = ? WHERE idproduct = ?", array(
                    $this->__get('altura'),
                    $this->__get('id')
                ));
            }
            if(!empty($this->__get('comprimento'))){
                $this->query("UPDATE tb_products SET vllength = ? WHERE idproduct = ?", array(
                    $this->__get('comprimento'),
                    $this->__get('id')
                ));
            }
            if(!empty($this->__get('peso'))){
                $this->query("UPDATE tb_products SET vlweight = ? WHERE idproduct = ?", array(
                    $this->__get('peso'),
                    $this->__get('id')
                ));
            }
            if(!empty($this->__get('url'))){
                $this->query("UPDATE tb_products SET desurl = ? WHERE idproduct = ?", array(
                    $this->__get('url'),
                    $this->__get('id')
                ));
            }
            if(!empty($this->__get('descricao'))){
                $this->query("UPDATE tb_products SET descricao = ? WHERE idproduct = ?", array(
                    $this->__get('descricao'),
                    $this->__get('id')
                ));
            }

            if(!empty($this->__get('photo'))){
                $this->query("UPDATE tb_products SET photo = ? WHERE idproduct = ?", array(
                    $this->__get('photo'),
                    $this->__get('id')
                ));
            }

            
            
        }

        public function delete():void
        {
            $this->query('DELETE FROM tb_products WHERE idproduct = ?', array($this->__get('id')));
        }
    }


?>