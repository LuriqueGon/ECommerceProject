<?php

namespace App\Models;
use MF\Model\DAO;
use MF\Model\Container;

    Class Cart extends DAO
    {

        protected $idCart ;
        protected $idSession ;
        protected $idUser ;
        protected $zipCode ;
        protected $freight ;
        protected $nrdays ;

        const SESSION = 'Cart';

        public function getFromSessionId()
        {
            return $this->select('SELECT * FROM tb_carts WHERE dessessionid = ?', array(session_id()));
        }

        public function getCart()
        {
            return $this->select('SELECT * FROM tb_carts WHERE idcart = ?', array($this->__get('idCart')));
        }

        public function setToSession()
        {
            $cart = get_object_vars($this);
            unset($cart['db']);
            $_SESSION['Cart'] = $cart;
        }

        public function save()
        {
            $this->selectAll('CALL sp_carts_save(?, ?, ?, ?, ?, ?)', array(
                $this->__get('idCart'),
                $this->__get('idSession'),
                $this->__get('idUser'),
                $this->__get('zipCode'),
                $this->__get('freight'),
                $this->__get('nrdays')
            ));
        }

        public function addProduct(Product $produto):void
        {
            $this->query('INSERT INTO tb_cartsproducts (idcart, idproduct) VALUES (?,?)', array(
                $this->__get('idCart'),
                $produto->__get('id')
            ));
        }

        public function removeProduct(Product $produto, $all = false):void
        {
            if($all){
                $this->query('UPDATE tb_cartsproducts SET dtremoved = NOW() WHERE idcart = ? AND idproduct = ? AND dtremoved IS NULL', array(
                    $this->__get('idCart'),
                    $produto->__get('id')
                ));
            }else{
                $this->query('UPDATE tb_cartsproducts SET dtremoved = NOW() WHERE idcart = ? AND idproduct = ? AND dtremoved IS NULL LIMIT 1', array(
                    $this->__get('idCart'),
                    $produto->__get('id')
                ));
            }
            
        }

        public function getAllProducts()
        {
            return $this->selectAll(
                'SELECT *, count(a.idproduct) as quantity
                FROM tb_cartsproducts a
                LEFT JOIN tb_products b ON a.idproduct = b.idproduct
                WHERE a.idcart = ? AND a.dtremoved IS NULL GROUP BY a.idproduct',
            array(
                $this->__get('idCart')
            ));
        }

        
    }


?>