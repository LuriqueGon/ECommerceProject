<?php 

    namespace App;
    use PDO;
    use PDOException;

    class Connection
    {

        public static function getDB()
        {
            try
            {
                $conn = new PDO(
                    "mysql:host=localhost;". // Host do banco
                    "dbname=db_ecommerce;". //Nome do banco
                    "charset=utf8", //Tipo de char
                    "root", //Usuario
                    "root" //Senha
                );
                return $conn;
                
            }catch(PDOException $e)
            {
                // Tratativa de Erros
            }
        }
    }

?>