<?php

namespace App\Models;
use MF\Model\DAO;

    Class Order extends DAO
    {
        protected $idorder;
        protected $idcart;
        protected $iduser;
        protected $idstatus;
        protected $idaddress;
        protected $idpayment;
        protected $desparcelas;
        protected $vltotal;

        public function getAll()
        {
            return $this->selectAll('SELECT * , a.dtregister as dataRegistro
            FROM tb_orders a 
            INNER JOIN tb_ordersstatus b USING(idstatus) 
            INNER JOIN tb_carts c USING(idcart)
            INNER JOIN tb_users d ON d.iduser = a.iduser
            INNER JOIN tb_addresses e USING(idaddress)
            INNER JOIN tb_persons f ON f.idperson = d.idperson
            INNER JOIN tb_orderspayment g ON g.idpayment = a.idpayment
            ORDER BY a.dtregister DESC');
        }

        public function save()
        {
            $results = $this->select("CALL sp_orders_save(?,?,?,?,?,?,?,?)", array(
                $this->__get('idorder'),
                $this->__get('idcart'),
                $this->__get('iduser'),
                $this->__get('idstatus'),
                $this->__get('idaddress'),
                $this->__get('idpayment'),
                $this->__get('vltotal'),
                $this->__get('desparcelas')
            ));

            if(!empty($results))
            {
                return $results;
            }
        }

        public function setStatus()
        {
            $this->query('UPDATE tb_orders SET idstatus = ? WHERE idorder = ?', array(
                $this->__get('idstatus'),
                $this->__get('idorder')
            ));
        }

        public function setPayment()
        {
            $this->query('UPDATE tb_orders SET idpayment = ? WHERE idorder = ?', array(
                $this->__get('idpayment'),
                $this->__get('idorder')
            ));
        }

        

        public function getOrder($idorder = "")
        {
            if(empty($idorder)) $idorder = $this->__get('idorder');
            $results = $this->select("
                SELECT * , a.dtregister as dataRegistro
                FROM tb_orders a 
                INNER JOIN tb_ordersstatus b USING(idstatus) 
                INNER JOIN tb_carts c USING(idcart)
                INNER JOIN tb_users d ON d.iduser = a.iduser
                INNER JOIN tb_addresses e USING(idaddress)
                INNER JOIN tb_persons f ON f.idperson = d.idperson
                INNER JOIN tb_orderspayment g ON g.idpayment = a.idpayment
                WHERE a.idorder = ? 
		    ", array(
                $idorder
            ));

            if(!empty($results))
            {
                return $results;
            }
        }

        public function getOrders()
        {
            $results = $this->selectAll("
                SELECT *, a.dtregister as dataRegistro
                FROM tb_orders a 
                INNER JOIN tb_ordersstatus b USING(idstatus) 
                INNER JOIN tb_carts c USING(idcart)
                INNER JOIN tb_users d ON d.iduser = a.iduser
                INNER JOIN tb_addresses e USING(idaddress)
                INNER JOIN tb_persons f ON f.idperson = d.idperson
                INNER JOIN tb_orderspayment g ON g.idpayment = a.idpayment
                WHERE a.iduser = ? ORDER BY a.dtregister DESC
		    ", array(
                $this->__get('iduser')
            ));

            if(!empty($results))
            {
                return $results;
            }
        }

        public function delete()
        {
            $this->query('DELETE FROM tb_orders WHERE idorder = ?', array(
                $this->__get('idorder')
            ));
        }
        
    }


?>