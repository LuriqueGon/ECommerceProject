<?php

    namespace App\Models;
    use MF\Model\Model;

    Class Message extends Model
    {

        public static function setMessage($message, $type, $redirect = "/", $hash = '')
        {
            if(isset($message) && !empty($message))
            {
                $_SESSION['Message']['type'] = $type;
                $_SESSION['Message']['msg'] = $message;
                $_SESSION['Message']['time'] = 1;

                if($redirect == "back")
                {
                    header('location: '. $_SERVER['HTTP_REFERER']. $hash);
                }else
                {
                    header('location: '. $redirect. $hash);
                }
            }
        }
        
        public static function getMessage()
        {
            if(!empty($_SESSION['Message']['msg']))
            {

                return [
                    
                    "type" => $_SESSION['Message']['type'],
                    "msg" => $_SESSION['Message']['msg'],
                    "time" => $_SESSION['Message']['time']
                ];
            }else
            {
                return false;
            }
        }

        public static function cleanMessage()
        {
            $_SESSION['Message']['msg'] = "";
            $_SESSION['Message']['type'] = "";
            $_SESSION['Message']['time'] = "";
            unset($_SESSION['Message']['msg']);
            unset($_SESSION['Message']['type']);
            unset($_SESSION['Message']['time']);
        }
       
    }


?>