<?php

namespace App\Models;
use MF\Model\DAO;
use App\Models\Mailer;

    Class User extends DAO
    {
        protected $id;
        protected $login;
        protected $password;
        protected $repassword;
        protected $nome;
        protected $email;
        protected $telefone;
        protected $inAdmin = 0;
        protected $idPerson;
        protected $perfil;
        protected $idDecrypt;
        protected $oldPass;
        protected $remember = false;

        const SECRET = "ECOMMERCEPHP7TXT";
        const SECRET_IV = "ECOMMERCEPHP7TXT_IV";

        public static function checkLogin():bool
        {
            if(isset($_SESSION['User']['iduser']) && (int) $_SESSION['User']['iduser'] > 0) return true;
            else return false;
        }

        public function getAll()
        {
            return $this->selectAll('SELECT * FROM tb_users INNER JOIN tb_persons b USING(idperson) ORDER BY tb_users.dtregister DESC');
        }

        public function findById()
        {
            return $this->select('SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = ?', array($this->__get('id')));
        }

        public function findByEmail()
        {
            return $this->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE b.desemail = ?", array($this->__get('email')));
        }

        public function findByLogin()
        {
            return $this->select("SELECT * FROM tb_users WHERE deslogin = ?", array($this->__get('login')));
        }

        public function findByTel()
        {
            return $this->select("SELECT * FROM tb_persons WHERE nrphone = ?", array($this->__get('telefone')));
        }
        public function getEmailByIdPerson()
        {
            return $this->select('SELECT * FROM tb_persons WHERE idperson = ?', array(
                $this->__get('idPerson')
            ))['desemail'];
        }
        
        public function getIdByIdPerson()
        {
            return $this->select('SELECT a.iduser FROM tb_users a INNER JOIN tb_persons b ON a.idperson = b.idperson WHERE a.idperson = ?', array(
                $this->__get('idPerson')
            ))['iduser'];
        }

        public function getIdByDecrypt()
        {
            return $this->select(
                "SELECT *
                FROM tb_userspasswordsrecoveries a
                INNER JOIN tb_users b USING(iduser)
                INNER JOIN tb_persons c USING(idperson)
                WHERE
				a.idrecovery = ?
				AND
				a.dtrecovery IS NULL
				AND
				DATE_ADD(a.dtregister, INTERVAL 1 HOUR) >= NOW();
		", array($this->__get('idDecrypt')));
        }

        public function getIdPerson():int
        {
            return $this->select('SELECT idperson FROM tb_users WHERE iduser = ?', array($this->__get('id')))['idperson'];
        }

        public function getIdPersonByEmail():int
        {
            return $this->select('SELECT idperson FROM tb_persons WHERE desemail = ?', array($this->__get('email')))['idperson'];
        }

        public function getIdPersonById():int
        {
            return $this->select('SELECT idperson FROM tb_users WHERE iduser = ?', array($this->__get('id')))['idperson'];
        }

        
        

        public function activeById():bool
        {
            return $this->rawQuery('UPDATE tb_users SET ativo = 1 WHERE iduser = ?', array($this->__get('id')));
        }

        public function desativeById():bool
        {
            return $this->rawQuery('UPDATE tb_users SET ativo = 0 WHERE iduser = ?', array($this->__get('id')));
        }

        public function deleteById():bool
        {
            $this->__set('idPerson', $this->getIdPersonById());
            $this->rawQuery('DELETE FROM tb_users WHERE idperson = ?', array(
                $this->__get('idPerson')
            ));
            return $this->rawQuery('DELETE FROM tb_persons WHERE idperson = ?', array(
                $this->__get('idPerson')
            ));
                       
        }

        public function login ()
        {
            return $this->select(
                'SELECT * FROM tb_users a 
                INNER JOIN tb_persons b USING(idperson) 
                INNER JOIN tb_addresses c USING(idperson) 
                WHERE a.deslogin = ? AND a.despassword = ? AND a.ativo = 1', array(
                    $this->__get('login'),
                    $this->__get('password')
                )
            );
        }

        public function register():bool
        {
            if(!$this->findByEmail()){
                if(!$this->findByLogin()){
                    if(!$this->findByTel()){
                        $this->cadastrarPerson();
                        $this->__set('idPerson', $this->getIdPersonByEmail());
                        if($this->cadastrarUser())return true;
                        else return false;
                    }

                }
            }

            return false;
        }

        public function validarDados($type = 'register')
        {
            
            if(empty($this->__get('login')) || strlen($this->__get('login')) < 6) Message::setMessage('Preencha o login corretamente <br> Minimo de 6 caracteres', 'danger', 'back');
            if(empty($this->__get('password') || strlen($this->__get('password')) < 8)) Message::setMessage('Preencha a Senha corretamente <br> Minimo de 8 caracteres', 'danger', 'back');

            if($type === 'register'){

                if(empty($this->__get('email')) || strlen($this->__get('email')) < 10) Message::setMessage('Preencha o email corretamente', 'danger', 'back');

                if(empty($this->__get('nome')) || strlen($this->__get('nome')) < 3) Message::setMessage('Preencha o Nome corretamente <br> Minimo de 3 caracteres', 'danger', 'back');

                if(empty($this->__get('telefone')) || strlen($this->__get('telefone')) < 10) Message::setMessage('Preencha o Telefone corretamente. <br> Com seu DDD  <br> Minimo de 10 caracteres', 'danger', 'back');

                if(empty($this->__get('repassword')) || strlen($this->__get('repassword')) < 8) Message::setMessage('Preencha a Confirma????o de Senha corretamente', 'danger', 'back');

                $this->__set('repassword', md5($this->__get('repassword')));
            }

            $this->__set('password', md5($this->__get('password')));
            
        }

        private function cadastrarPerson():void
        {
            $query = "INSERT INTO tb_persons (desperson, desemail, nrphone) VALUES (?,?,?)";
            $params = array(
                $this->__get('nome'),
                $this->__get('email'),
                $this->__get('telefone')
            );
            $this->query($query, $params);

        }

        private function cadastrarUser():bool
        {
            $query = "INSERT INTO tb_users (idperson, deslogin, despassword, inadmin) VALUES (?,?,?,?)";
            $params = array(
                $this->__get('idPerson'),
                $this->__get('login'),
                $this->__get('password'),
                $this->__get('inAdmin')
            );
            if($this->rawQuery($query, $params)) return true;
            else return false;
        }

        public function savePhoto()
        {
            $this->query('UPDATE tb_persons SET perfil = ? WHERE idperson = ?', array(
                $this->__get('perfil'),
                $this->__get('idPerson')
            ));
        }
        private function haveLogin()
        {
            return $this->select('SELECT * FROM tb_users WHERE deslogin = ?', array(
                $this->__get('login')
            ));
        }

        public function edit()
        {
            if(!empty($this->__get('email'))){
                $this->changeEmail();
                
            }
            if(!empty($this->__get('password'))){
                $this->query("UPDATE tb_users SET despassword = ? WHERE iduser = ?", array(md5($this->__get('password')), $this->__get('id')));
            }

            if(!empty($this->__get('login'))){
                if(empty($this->haveLogin()))
                {
                    $this->query("UPDATE tb_users SET deslogin = ? WHERE iduser = ?",array($this->__get('login'),$this->__get('id')));
                }
            }
            
            if($this->__get('inAdmin') == "on"){
                $this->query("UPDATE tb_users SET inAdmin = 1 WHERE iduser = ?", array($this->__get('id')));
            }
            if($this->__get('inAdmin') == "off"){
                $this->query("UPDATE tb_users SET inAdmin = 0 WHERE iduser = ?", array($this->__get('id')));
            }

            if(!empty($this->__get('nome'))){
                $this->query("UPDATE tb_persons SET desperson = ? WHERE idperson = ?",array($this->__get('nome'),$this->__get('idPerson')));
            }

            if(!empty($this->__get('telefone'))){
                $this->query("UPDATE tb_persons SET nrphone = ? WHERE idperson = ?",array($this->__get('telefone'),$this->__get('idPerson')));
            }

            if(!empty($this->__get('perfil'))){
                $this->query("UPDATE tb_persons SET perfil = ? WHERE idperson = ?",array($this->__get('perfil'),$this->__get('idPerson')));
            }

           
        }

        private function changeEmail()
        {
            $newEmail = $this->getEmailByIdPerson();
            if($newEmail != $this->__get('email')){
                if(empty($this->findByEmail())){
                    $this->query("UPDATE tb_persons SET desemail = ? WHERE idperson = ?",array($this->__get('email'),$this->__get('idPerson')));
                }
            }
            
        }

        public function forgot():bool
        {
            $user = $this->findByEmail();

            
            if(count($user) === 0) return false;

            $results = $this->select('CALL sp_userspasswordsrecoveries_create(?,?)', array($user['iduser'],$_SERVER['REMOTE_ADDR']));

            if(count($results) === 0) return false;

            $code = base64_encode(openssl_encrypt($results['idrecovery'], 'AES-128-CBC', pack("a16", User::SECRET), 0, pack("a16", User::SECRET_IV)));
            $link = "http://localhost:8080/login/forgotPassword/reset?code=".$code;

            $msg = $this->getmsg($link, $user['desperson']);
            Mailer::sendMail($user['desemail'], $user['desperson'],"Redefini????o de Senha", $msg);
            return true;
        }

        public function testPassword()
        {
            return $this->select('SELECT * FROM tb_users WHERE iduser = ? AND despassword = ?', array(
                $this->__get('id'),
                $this->__get('oldPass')
            ));
        }

        public function decryptCode($code):String
        {
            $code = base64_decode($code);

		    $idrecovery = openssl_decrypt($code, 'AES-128-CBC', pack("a16", User::SECRET), 0, pack("a16", User::SECRET_IV));

            return $idrecovery;
        }

        public function recoveryPassword()
        {
            $this->setForgotUsed();

            $this->query('UPDATE tb_users SET despassword = ? WHERE iduser = ?', array($this->__get('password'), $this->__get('id')));
        }

        private function setForgotUsed():void
        {
            $this->query('UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery = ?', array($this->__get('idDecrypt')));
        }

        private function getMsg(String $link, String $user):string
        {
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> <html xmlns="http://www.w3.org/1999/xhtml"> <head> <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> <meta name="viewport" content="width=device-width"/> <title>Airmail Ping</title> <style type="text/css"> *{margin:0; padding:0; font-family: Helvetica, Arial, sans-serif;}img{max-width: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;}.image-fix{display:block;}.collapse{margin:0; padding:0;}body{-webkit-font-smoothing:antialiased; -webkit-text-size-adjust:none; width: 100%!important; height: 100%; text-align: center; color: #747474; background-color: #ffffff;}h1,h2,h3,h4,h5,h6{font-family: Helvetica, Arial, sans-serif; line-height: 1.1;}h1 small, h2 small, h3 small, h4 small, h5 small, h6 small{font-size: 60%; line-height: 0; text-transform: none;}h1{font-weight:200; font-size: 44px;}h2{font-weight:200; font-size: 32px; margin-bottom: 14px;}h3{font-weight:500; font-size: 27px;}h4{font-weight:500; font-size: 23px;}h5{font-weight:900; font-size: 17px;}h6{font-weight:900; font-size: 14px; text-transform: uppercase;}.collapse{margin:0!important;}td, div{font-family: Helvetica, Arial, sans-serif; text-align: center;}p, ul{margin-bottom: 10px; font-weight: normal; font-size:14px; line-height:1.6;}p.lead{font-size:17px;}p.last{margin-bottom:0px;}ul li{margin-left:5px; list-style-position: inside;}a{color: #747474; text-decoration: none;}a img{border: none;}.head-wrap{width: 100%; margin: 0 auto; background-color: #f9f8f8; border-bottom: 1px solid #d8d8d8;}.head-wrap *{margin: 0; padding: 0;}.header-background{background: repeat-x url(https://www.filepicker.io/api/file/wUGKTIOZTDqV2oJx5NCh) left bottom;}.header{height: 42px;}.header .content{padding: 0;}.header .brand{font-size: 16px; line-height: 42px; font-weight: bold;}.header .brand a{color: #464646;}.body-wrap{width: 505px; margin: 0 auto; background-color: #ffffff;}.soapbox .soapbox-title{font-size: 21px; color: #464646; padding-top: 35px;}.content .status-container.single .status-padding{width: 80px;}.content .status{width: 90%;}.content .status-container.single .status{width: 300px;}.status{border-collapse: collapse; margin-left: 15px; color: #656565;}.status .status-cell{border: 1px solid #b3b3b3; height: 50px;}.status .status-cell.success, .status .status-cell.active{height: 65px;}.status .status-cell.success{background: #f2ffeb; color: #51da42;}.status .status-cell.success .status-title{font-size: 15px;}.status .status-cell.active{background: #fffde0; width: 135px;}.status .status-title{font-size: 16px; font-weight: bold; line-height: 23px;}.status .status-image{vertical-align: text-bottom;}.body .body-padded, .body .body-padding{padding-top: 34px;}.body .body-padding{width: 41px;}.body-padded, .body-title{text-align: left;}.body .body-title{font-weight: bold; font-size: 17px; color: #464646;}.body .body-text .body-text-cell{text-align: left; font-size: 14px; line-height: 1.6; padding: 9px 0 17px;}.body .body-text-cell a{color: #464646; text-decoration: underline;}.body .body-signature-block .body-signature-cell{padding: 25px 0 30px; text-align: left;}.body .body-signature{font-family: "Comic Sans MS", Textile, cursive; font-weight: bold;}.footer-wrap{width: 100%; margin: 0 auto; clear: both !important; background-color: #e5e5e5; border-top: 1px solid #b3b3b3; font-size: 12px; color: #656565; line-height: 30px;}.footer-wrap .container{padding: 14px 0;}.footer-wrap .container .content{padding: 0;}.footer-wrap .container .footer-lead{font-size: 14px;}.footer-wrap .container .footer-lead a{font-size: 14px; font-weight: bold; color: #535353;}.footer-wrap .container a{font-size: 12px; color: #656565;}.footer-wrap .container a.last{margin-right: 0;}.footer-wrap .footer-group{display: inline-block;}.container{display: block !important; max-width: 505px !important; clear: both !important;}.content{padding: 0; max-width: 505px; margin: 0 auto; display: block;}.content table{width: 100%;}.clear{display: block; clear: both;}table.full-width-gmail-android{width: 100% !important;}</style> <style type="text/css" media="only screen"> @media only screen{table[class*="head-wrap"], table[class*="body-wrap"], table[class*="footer-wrap"]{width: 100% !important;}td[class*="container"]{margin: 0 auto !important;}}@media only screen and (max-width: 505px){*[class*="w320"]{width: 320px !important;}table[class="soapbox"] td[class*="soapbox-title"], table[class="body"] td[class*="body-padded"]{padding-top: 24px;}}</style> </head> <body bgcolor="#ffffff"> <div align="center"> <table class="head-wrap w320 full-width-gmail-android" bgcolor="#f9f8f8" cellpadding="0" cellspacing="0" border="0"> <tr> <td background="https://www.filepicker.io/api/file/UOesoVZTFObSHCgUDygC" bgcolor="#ffffff" width="100%" height="8" valign="top"><!--[if gte mso 9]> <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="mso-width-percent:1000;height:8px;"> <v:fill type="tile" src="https://www.filepicker.io/api/file/UOesoVZTFObSHCgUDygC" color="#ffffff"/> <v:textbox inset="0,0,0,0"><![endif]--> <div height="8"> </div><!--[if gte mso 9]> </v:textbox> </v:rect><![endif]--> </td></tr><tr class="header-background"> <td class="header container" align="center"> <div class="content"> <span class="brand"> <a href="#"> ECommerce </a> </span> </div></td></tr></table> <table class="body-wrap w320"> <tr> <td></td><td class="container"> <div class="content"> <table cellspacing="0"> <tr> <td> <table class="soapbox"> <tr> <td class="soapbox-title">Recupera????o de Senha</td></tr></table> <table class="body"> <tr> <td class="body-padding"></td><td class="body-padded"> <div class="body-title">Ol?? '.$user.',</div><table class="body-text"> <tr> <td class="body-text-cell"> Para redefinir a sua senha acesse o link <a href="'.$link.'">'.$link.'</a>. </td></tr></table> <div><!--[if mso]> <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="#" style="height:38px;v-text-anchor:middle;width:230px;" arcsize="11%" strokecolor="#407429" fill="t"> <v:fill type="tile" src="https://www.filepicker.io/api/file/N8GiNGsmT6mK6ORk00S7" color="#41CC00"/> <w:anchorlock/> <center style="color:#ffffff;font-family:sans-serif;font-size:17px;font-weight:bold;">Review Account Settings</center> </v:roundrect><![endif]--><a href="'.$link.'" style="background-color:#41CC00;background-image:url(https://www.filepicker.io/api/file/N8GiNGsmT6mK6ORk00S7);border:1px solid #407429;border-radius:4px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:17px;font-weight:bold;text-shadow: -1px -1px #47A54B;line-height:38px;text-align:center;text-decoration:none;width:230px;-webkit-text-size-adjust:none;mso-hide:all;">Redefinir Senha</a></div><table class="body-signature-block"> <tr> <td class="body-signature-cell"> <p>Obrigado!</p><br><br><br><p style="color:red; font-size: 1.2rem;"> Caso voc?? n??o tenha solicitado altera????o de senha, <b><e>IGNORE!!!</e></b> Est?? Mensagem</p></td></tr></table> </td><td class="body-padding"></td></tr></table> </td></tr></table> </div></td><td></td></tr></table> <table class="footer-wrap w320 full-width-gmail-android" bgcolor="#e5e5e5"> <tr> <td></td><td class="container"> <div class="content footer-lead"> <a href="#"><b>Get in touch</b></a> if you have any questions or feedback </div></td><td></td></tr></table> <table class="footer-wrap w320 full-width-gmail-android" bgcolor="#e5e5e5"> <tr> <td></td><td class="container"> <div class="content"> <a href="#">Contact Us</a>&nbsp;&nbsp;|&nbsp;&nbsp; <span class="footer-group"> <a href="#">Facebook</a>&nbsp;&nbsp;|&nbsp;&nbsp; <a href="#">Twitter</a>&nbsp;&nbsp;|&nbsp;&nbsp; <a href="#">Support</a> </span> </div></td><td></td></tr></table> </div></body> </html> ';
        }

    }
