<?php 

    namespace App\Controllers;
    use MF\Controller\Action;
    use MF\Model\Container;

    class BoletoController extends Action
    {
        public function boleto()
        {
            $dias_de_prazo_para_pagamento = 15;
            $taxa_boleto = 2.95;
            $data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 
            $valor_cobrado = $_SESSION['Cart']['total'] + $_SESSION['Cart']['freight']; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
            $valor_cobrado = str_replace(",", ".",$valor_cobrado);
            $valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

            $dadosboleto["inicio_nosso_numero"] = "99";  // Inicio do Nosso numero -> 99 - Cobran�a Direta(Carteira 5) ou 0 - Cobran�a Simples(Carteira 1)
            $dadosboleto["nosso_numero"] = $_SESSION['OrderInfo'][$_GET['idorder']]['idorder'];  // Nosso numero sem o DV - REGRA: M�ximo de 7 caracteres!
            $dadosboleto["numero_documento"] = $_SESSION['OrderInfo'][$_GET['idorder']]['idorder'];	// Num do pedido ou do documento
            $dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
            $dadosboleto["data_documento"] = date("d/m/Y"); // Data de emiss�o do Boleto
            $dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
            $dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com v�rgula e sempre com duas casas depois da virgula
            $orderInfo = $_SESSION['OrderInfo'][$_GET['idorder']];
            // DADOS DO SEU CLIENTE
            $dadosboleto["sacado"] = $_SESSION['User']['desperson'];
            $dadosboleto["endereco1"] = $orderInfo['descity']. ", " . $orderInfo['desdistrict']. "-". $orderInfo['desstate'] . "- CEP: ". $orderInfo['deszipcode'];
            $dadosboleto["endereco2"] = "";

            // INFORMACOES PARA O CLIENTE
            $dadosboleto["demonstrativo1"] = "Pagamento de Compra na Loja E-Commerce Stores";
            $dadosboleto["demonstrativo2"] = "Mensalidade referente a uma compra em E-Commerce Stores<br>Taxa bancária - R$ ".number_format($taxa_boleto, 2, ',', '');
            $dadosboleto["demonstrativo3"] = "";
            $dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de 2% apóss o vencimento";
            $dadosboleto["instrucoes2"] = "- Receber até 2 dias apóss o vencimento";
            $dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: SuporteEcommerce@Ecommerce.com.br";
            $dadosboleto["instrucoes4"] = "";

            $dadosboleto["quantidade"] = "1";
            $dadosboleto["valor_unitario"] = "";
            $dadosboleto["aceite"] = "N";		
            $dadosboleto["especie"] = "R$";
            $dadosboleto["especie_doc"] = "DM";


            // ---------------------- DADOS FIXOS DE CONFIGURA��O DO SEU BOLETO --------------- //


            // DADOS DA SUA CONTA - NOSSA CAIXA
            /*$dadosboleto["agencia"] = "0033"; // Num da agencia, sem digito
            $dadosboleto["conta"] = "0001131"; 	// Num da conta, sem digito
            $dadosboleto["conta_dv"] = "1"; 	// Digito do Num da conta*/

            // DADOS PERSONALIZADOS - CEF
            $dadosboleto["agencia"] = "0033"; // Num da agencia, sem digito
            $dadosboleto["conta_cedente"] = "001131"; // ContaCedente do Cliente, sem digito (Somente 6 digitos)
            $dadosboleto["conta_cedente_dv"] = "1"; // Digito da ContaCedente do Cliente
            $dadosboleto["carteira"] = "5";  // C�digo da Carteira -> 5-Cobran�a Direta ou 1-Cobran�a Simples
            $dadosboleto["modalidade_conta"] = "04";  // modalidade da conta 02 posi��es

            // SEUS DADOS
            $dadosboleto["identificacao"] = "Sistema de Compras Online | E-Commerce Stores";
            $dadosboleto["cpf_cnpj"] = "710.799.694-03";
            $dadosboleto["endereco"] = "Abreu e Lima - Caétes II - PE";
            $dadosboleto["cidade_uf"] = "Abreu e lima - PE";
            $dadosboleto["cedente"] = "Comer muita puta";

            // N�O ALTERAR!
            include("include/funcoes_nossacaixa.php"); 
            include("include/layout_nossacaixa.php");
        }
    }
