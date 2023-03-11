<?php

namespace App\Models;
use MF\Model\DAO;
use MF\Model\Container;

    Class Cart extends DAO
    {

        protected $idCart ;
        protected $idSession ;
        protected $idUser ;
        protected $zipCode ;
        protected $freight ;
        protected $nrdays ;

        public static function getFromSession():object
        {
            $cart = Container::getModel('cart');

            if(isset($_SESSION['Cart']) && (int)$_SESSION['Cart']['idCart'] > 0){

                $cart->__set('idCart', $_SESSION['Cart']['idCart']);
                $result = $cart->getCart();
                $params = array(
                    'idCart' => $result['idcart'],
                    'idSession' => $result['dessessionid'],
                    'idUser' => $result['iduser'],
                    'zipCode' => $result['deszipcode'],
                    'freight' => $result['vlfreight'],
                    'nrdays' => $result['nrdays'],
                );
                $cart->setData($params);

            }else{
                $cart->getFromSessionId();

                if(!(int)$cart->__get('idCart')){

                    $data = array(
                        'idSession' => session_id()
                    );

                    if(User::checkLogin()) $data['idUser'] = $_SESSION['User']['iduser'];
                    $cart->setData($data);
                    $cart->save();
                    $cart->__set('idCart', $cart->getIdCart());
                }
                
                $cart->setToSession($cart);
            }
            
            return $cart;
            
        }

        public function resetCart()
        {
            session_regenerate_id();
            $cart = Container::getModel('cart');
            $data = array(
                'idSession' => session_id()
            );

            if(User::checkLogin()) $data['idUser'] = $_SESSION['User']['iduser'];
            $cart->setData($data);
            $cart->save();
            $cart->__set('idCart', $cart->getIdCart());
            $cart->setToSession($cart);
            return $cart;
        }

        public function getIdCart()
        {
            return $this->select('SELECT idcart FROM tb_carts WHERE dessessionid = ?', array(session_id()))['idcart'];
        }

        public function getFromSessionId():void
        {
            $result = $this->select('SELECT * FROM tb_carts WHERE dessessionid = ?', array(session_id()));

            if(!empty($result)){
                $params = array(
                    'idCart' => $result['idcart'],
                    'idSession' => $result['dessessionid'],
                    'idUser' => $result['iduser'],
                    'zipCode' => $result['deszipcode'],
                    'freight' => $result['vlfreight'],
                    'nrdays' => $result['nrdays'],
                );
                $this->setData($params);
            }
        }

        public function getCart()
        {
            return $this->select('SELECT * FROM tb_carts WHERE idcart = ?', array($this->__get('idCart')));
        }

        public function setToSession($cart):void
        {
            $cart = get_object_vars($cart);
            unset($cart['db']);
            $_SESSION['Cart'] = $cart;
        }

        public function save():void
        {
            $this->selectAll('CALL sp_carts_save(?, ?, ?, ?, ?, ?)', array(
                $this->__get('idCart'),
                $this->__get('idSession'),
                $this->__get('idUser'),
                $this->__get('zipCode'),
                $this->__get('freight'),
                $this->__get('nrdays')
            ));
        }

        public function addProduct(Product $produto):void
        {
            $this->query('INSERT INTO tb_cartsproducts (idcart, idproduct) VALUES (?,?)', array(
                $this->__get('idCart'),
                $produto->__get('id')
            ));
            
            $this->updateFreight();

        }

        public function removeProduct(Product $produto, $all = false):void
        {
            if($all){
                $this->query('UPDATE tb_cartsproducts SET dtremoved = NOW() WHERE idcart = ? AND idproduct = ? AND dtremoved IS NULL', array(
                    $this->__get('idCart'),
                    $produto->__get('id')
                ));
            }else{
                $this->query('UPDATE tb_cartsproducts SET dtremoved = NOW() WHERE idcart = ? AND idproduct = ? AND dtremoved IS NULL LIMIT 1', array(
                    $this->__get('idCart'),
                    $produto->__get('id')
                ));
            }
            $this->updateFreight();
            
        }

        public function sellItens()
        {
            $this->query('UPDATE tb_cartsproducts SET dtremoved = NOW() WHERE idcart = ? AND dtremoved IS NULL', array(
                $this->__get('idCart')
            ));
        }

        public function getAllProducts()
        {
            return $this->selectAll(
                'SELECT *, count(a.idproduct) as quantity, sum(b.vlprice) as total
                FROM tb_cartsproducts a
                LEFT JOIN tb_products b ON a.idproduct = b.idproduct
                WHERE a.idcart = ? AND a.dtremoved IS NULL 
                GROUP BY a.idproduct, b.vlprice',
            array(
                $this->__get('idCart')
            ));
        }

        public function getAllProductsOrder()
        {
            return $this->selectAll(
                'SELECT *, count(a.idproduct) as quantity, sum(b.vlprice) as total
                FROM tb_cartsproducts a
                LEFT JOIN tb_products b ON a.idproduct = b.idproduct
                WHERE a.idcart = ? 
                GROUP BY a.idproduct, b.vlprice',
            array(
                $this->__get('idCart')
            ));
        }

        

        public function getProductsTotals()
        {
            $results = $this->select('
                SELECT SUM(vlprice) as vlprice, SUM(vlwidth) as vlwidth, SUM(vlheight) as vlheight, SUM(vllength) as vllength, SUM(vlweight) as vlweight
                FROM tb_products a
                INNER JOIN tb_cartsproducts b ON a.idproduct = b.idproduct
                WHERE b.idcart = ? AND dtremoved IS NULL
            ', array(
                $this->__get('idCart')
            ));

            if(!empty($results)) return $results;
            else return [];
        }


        public function setFreight()
        {
            if(!empty($this->getAllProducts())){

            
                $zipCode = str_replace('-','',$this->__get('zipCode'));

                $totals = $this->getProductsTotals();

                if ($totals['vlheight'] < 2) $totals['vlheight'] = 2.00;
                if ($totals['vlheight'] > 100) $totals['vlheight'] = 100.00;

                if ($totals['vllength'] < 16) $totals['vllength'] = 16.00;
                if ($totals['vllength'] > 100) $totals['vllength'] = 100.00;

                if ($totals['vlwidth'] < 11) $totals['vlwidth'] = 11.00;
                if ($totals['vlwidth'] > 100) $totals['vlwidth'] = 100.00;

                while ($totals['vllength'] + $totals['vlheight'] + $totals['vlwidth'] > 200)
                {
                    $totals['vllength']--;
                    $totals['vlheight']--;
                    $totals['vlwidth']--;
                }

                if ($totals['vlprice'] > 10000) $totals['vlprice'] = 10000;
                if(!empty($totals)){
                    $qs = http_build_query(array(
                        'nCdEmpresa'=>'',
                        'sDsSenha'=>'',
                        'nCdServico'=>'40010',
                        'sCepOrigem'=>'52050660',
                        'sCepDestino'=>$zipCode,
                        'nVlPeso'=>$totals['vlweight'],
                        'nCdFormato'=>1,
                        'nVlComprimento'=>$totals['vllength'],
                        'nVlAltura'=>$totals['vlheight'],
                        'nVlLargura'=>$totals['vlwidth'],
                        'nVlDiametro'=>0,
                        'sCdMaoPropria'=>'S',
                        'nVlValorDeclarado'=>$totals['vlprice'],
                        'sCdAvisoRecebimento'=>'S'
                    ));
                    
                    $xml = simplexml_load_file('http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx/CalcPrecoPrazo?'.$qs);
                    $result = $xml->Servicos->cServico;

                    $this->__set('nrdays', (string)$result->PrazoEntrega[0]);
                    $this->__set('freight', number_format((float)$result->Valor,2,'.',''));
                    
                    $_SESSION['Cart']['freight'] =  number_format((float)$result->Valor,2,'.','');
                    $_SESSION['Cart']['nrdays'] = (string)$result->PrazoEntrega[0];
                    $_SESSION['Cart']['zipCode'] = (string)$zipCode;

                    $this->save();
                    
                    return $result;

                }
            }
        }
        
        public function updateFreight()
        {
            if(!empty($this->__get('zipCode'))){
                if(!empty($this->getAllProducts())){
                    $this->setFreight();
                }
            }
        }
        
    }


?>