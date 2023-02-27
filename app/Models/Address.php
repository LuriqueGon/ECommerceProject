<?php

namespace App\Models;
use MF\Model\DAO;

    Class Address extends DAO
    {
        protected $id;
        protected $idPerson;
        protected $desaddress;
        protected $descomplement;
        protected $descity;
        protected $desstate;
        protected $descountry;
        protected $nrzipcode;
        protected $dtregister;

        public function getAddress()
        {
            $result = $this->selectAll('SELECT * FROM tb_addresses WHERE idperson = ?', array($this->__get('idPerson')));

            if(!empty($result)){
                return $result;
            }else{
                return array(
                    'idPerson' => '',
                    'desaddress' => '',
                    'descomplement' => '',
                    'descity' => '',
                    'desstate' => '',
                    'descountry' => '',
                    'nrzipcode' => ''
                );
            }

        }
    }


?>