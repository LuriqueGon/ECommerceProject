<?php

namespace App\Models;
use MF\Model\DAO;

    Class Coment extends DAO
    {
        protected $id;
        protected $idUser;
        protected $idProd;
        protected $subject;
        protected $coment;
        protected $rating;
        protected $dtregister;

        public function getComent():array
        {
            return $this->selectAll('SELECT a.like, a.unlike,a.subject, a.coment, a.rating, a.dtregister, c.desperson, c.perfil FROM tb_coments a INNER JOIN tb_users b ON a.idUser = b.idUser INNER JOIN tb_persons c ON b.idperson = c.idperson WHERE a.idProduct = ?', array(
                $this->__get('idProd')
            ));
        }
    }


?>