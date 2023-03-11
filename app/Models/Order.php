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
        protected $vltotal;

        public function save()
        {
            $results = $this->select("CALL sp_orders_save(?,?,?,?,?,?)", array(
                $this->__get('idorder'),
                $this->__get('idcart'),
                $this->__get('iduser'),
                $this->__get('idstatus'),
                $this->__get('idaddress'),
                $this->__get('vltotal')
            ));

            if(!empty($results))
            {
                return $results;
            }
        }

        public function get()
        {
            $results = $this->select("
                SELECT * 
                FROM tb_orders a 
                INNER JOIN tb_ordersstatus b USING(idstatus) 
                INNER JOIN tb_carts c USING(idcart)
                INNER JOIN tb_users d ON d.iduser = a.iduser
                INNER JOIN tb_addresses e USING(idaddress)
                INNER JOIN tb_persons f ON f.idperson = d.idperson
                WHERE a.idorder = ?
		    ", array(
                $this->__get('idorder')
            ));

            if(!empty($results))
            {
                return $results;
            }
        }
    }


?>