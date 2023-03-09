<?php

    namespace App\Models;
    use MF\Model\Model;

    Class Message extends Model
    {

        public static function setMessage($message, $type, $redirect = "/", $hash = '')
        {
            if(isset($message) && !empty($message))
            {
                $_SESSION['type'] = $type;
                $_SESSION['msg'] = $message;
                $_SESSION['time'] = 1;

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
            if(!empty($_SESSION['msg']))
            {

                return [
                    
                    "type" => $_SESSION['type'],
                    "msg" => $_SESSION['msg'],
                    "time" => $_SESSION['time']
                ];
            }else
            {
                return false;
            }
        }

        public static function cleanMessage()
        {
            $_SESSION['msg'] = "";
            $_SESSION['type'] = "";
            $_SESSION['time'] = "";
            unset($_SESSION['msg']);
            unset($_SESSION['type']);
            unset($_SESSION['time']);
        }
       
    }


?>