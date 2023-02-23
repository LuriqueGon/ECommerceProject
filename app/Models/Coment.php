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
            return $this->selectAll("
                SELECT 
                    a.idcoment,a.like, a.unlike,a.subject, a.coment, a.rating, a.dtregister, 
                    c.desperson, c.perfil
                FROM tb_coments a INNER JOIN tb_users b ON a.idUser = b.idUser 
                INNER JOIN tb_persons c ON b.idperson = c.idperson 
                WHERE a.idProduct = ?
                ",
                array(
                $this->__get('idProd')
            ));
        }

        public function setComent():void
        {
            $this->query('INSERT INTO tb_coments (iduser, idproduct, subject, coment, rating) VALUES (?,?,?,?,?)', array(
                $this->__get('idUser'),
                $this->__get('idProd'),
                $this->__get('subject'),
                $this->__get('coment'),
                $this->__get('rating')
            ));
        }

        public function getIfComent():array
        {
            $result = $this->select('SELECT feedback FROM tb_comentsfeedback WHERE idComent = ? AND idUser = ?', array(
                $this->__get('id'),
                $this->__get('idUser')
            ));

            if($result){
                return array(
                    true,
                    $result['feedback']
                );
            }else{
                return array(
                    false
                );
            }
        }

        public function getLike():int
        {
            return $this->selectAll("SELECT count(feedback) as `like` FROM tb_comentsfeedback WHERE idcoment = ? AND feedback = 1", array($this->__get('id')))[0]['like'];
        }

        public function getUnlike():int
        {
            return $this->selectAll("SELECT count(feedback) as `like` FROM tb_comentsfeedback WHERE idcoment = ? AND feedback = 0", array($this->__get('id')))[0]['like'];
        }

        

        public function like():void
        {
            if($this->getIfComent()[0]) $this->desfazer();

            $this->query('INSERT INTO tb_comentsfeedback (idcoment, iduser, feedback) VALUES (?,?,1)',array(
                $this->__get('id'),
                $this->__get('idUser')
            ));
        }

        public function unlike():void
        {
            if($this->getIfComent()[0]) $this->desfazer();

            $this->query('INSERT INTO tb_comentsfeedback (idcoment, iduser, feedback) VALUES (?,?,0)',array(
                $this->__get('id'),
                $this->__get('idUser')
            ));
        }

        

        public function desfazer():void
        {
            $this->query('DELETE FROM tb_comentsfeedback WHERE idComent = ? AND idUser = ?', array(
                $this->__get('id'),
                $this->__get('idUser')
            ));
        }
    }


?>