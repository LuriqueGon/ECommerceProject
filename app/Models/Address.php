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
        protected $desdistrict;
        protected $desstate;
        protected $descountry;
        protected $deszipcode ;
        protected $desnumber;
        protected $dtregister;

        public function getAddress()
        {
            $result = $this->selectAll('SELECT * FROM tb_addresses WHERE idperson = ? ORDER BY dtregister DESC', array($this->__get('idPerson')));

            if(!empty($result)){
                return $result[0];
            }else{
                return array(
                    'idPerson' => '',
                    'desaddress' => '',
                    'descomplement' => '',
                    'descity' => '',
                    'desstate' => '',
                    'descountry' => '',
                    'deszipcode' => '',
                    'desdistrict' => '',
                    'desnumber' => ''
                );
            }

        }

        public static function getCEP($cep)
        {
            $cep = str_replace('-','',$cep);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://viacep.com.br/ws/$cep/json/");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $data = json_decode(curl_exec($ch), true);
            curl_close($ch);

            return $data;

        }

        public function loadFromCep($cep)
        {
            $endEmpty = array(
                'desaddress' =>'',
                'descomplement' =>'',
                'desdistrict' =>'',
                'descity' =>'',
                'deszipcode' =>'',
                'desstate' =>'',
                'descountry' =>'',
                'desnumber' =>''
            );
            if(empty($cep)) return $endEmpty;

            $data = Address::getCEP($cep);

            if(empty($data)) return $endEmpty;

            return array(
                'desaddress' =>$data['logradouro'],
                'descomplement' =>$data['complemento'],
                'desdistrict' =>$data['bairro'],
                'descity' =>$data['localidade'],
                'deszipcode' =>$data['cep'],
                'desstate' =>$data['uf'],
                'descountry' =>"Brasil",
                'desnumber' =>''
            );
        }

        public function save()
        {
            $results = $this->select("
                CALL sp_addresses_save(?,?,?,?,?,?,?,?,?,?)
            ", array(
                $this->__get('id'),
                $this->__get('idPerson'),
                $this->__get('desaddress'),
                $this->__get('descomplement'),
                $this->__get('descity'),
                $this->__get('desstate'),
                $this->__get('desnumber'),
                $this->__get('descountry'),
                $this->__get('deszipcode'),
                $this->__get('desdistrict')
            ));
        }

        public function getIdByPerson()
        {
            $result = $this->select('SELECT idaddress FROM tb_addresses WHERE idperson = ?', array(
                $this->__get('idPerson')
            ));

            if(empty($result)) return -1;
            else return $result['idaddress'];
        }

        public function getIdByPersonAndCEP()
        {
            $result = $this->select('SELECT idaddress FROM tb_addresses WHERE idperson = ? AND deszipcode = ?', array(
                $this->__get('idPerson'),
                $this->__get('deszipcode')
            ));

            if(empty($result)) return -1;
            else return $result['idaddress'];
        }

        
    }


?>