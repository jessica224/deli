<?php

//https://marcosmuniz.com.br/desenvolvimento-web/php/imprimindo-com-php-na-impressora-nao-fiscal-bematech-mp-20-mi/
ob_start();
error_reporting(E_ALL ^ E_NOTICE);
require_once '../vendor/autoload.php';
require_once '../App/Util/Util.php';

use App\Model\PedidosDao;
use App\Model\UsuarioDao;
use App\Model\BairrosDao;
use App\Util\Util;

date_default_timezone_set('America/Sao_Paulo');
//redirect_login();

/*
  function redirect_login() {
  global $template, $mpdf;
  if (!Util::sessao_existe()) {
  $mpdf->WriteHTML('impossivel o acesso');
  $mpdf->Output();
  exit();
  }
  }
 * 
 */

$cabecalho = "DATA IMPRESSÃO : {DATE d/m/Y}   PÁG. Nº [{PAGENO}]";

$ped = PedidosDao::getCPedido($_REQUEST["ped_chave"]);
$produto = PedidosDao::getProdutoPedido($_REQUEST["ped_chave"]);
$adicional = PedidosDao::getAdicionalPedido($_REQUEST["ped_chave"]);

if (!$ped) {
//$mpdf->WriteHTML('pedido não existe');
//$mpdf->Output();
    exit();
}

$cliente = UsuarioDao::getUsuarioBy_usu_codigo($ped['cli_codigo']);
$bairro = BairrosDao::getBairro($cliente['bai_codigo']);

$taxaEntrega = 0;
if ($ped['ped_forma_de_entrega'] == 'ENTREGAR') {
    $taxaEntrega = $bairro['bai_taxa'];
}



/*
 * Gerar um arquivo .txt para imprimir na impressora Bematech MP-20 MI
 */

$n_colunas = 40; // 40 colunas por linha

/**
 * Adiciona a quantidade necessaria de espaços no inicio 
 * da string informada para deixa-la centralizada na tela
 * 
 * @global int $n_colunas Numero maximo de caracteres aceitos
 * @param string $info String a ser centralizada
 * @return string
 */
function centraliza($info) {
    global $n_colunas;

    $aux = strlen($info);

    if ($aux < $n_colunas) {
// calcula quantos espaços devem ser adicionados
// antes da string para deixa-la centralizada
        $espacos = floor(($n_colunas - $aux) / 2);

        $espaco = '';
        for ($i = 0; $i < $espacos; $i++) {
            $espaco .= ' ';
        }

// retorna a string com os espaços necessários para centraliza-la
        return $espaco . $info;
    } else {
// se for maior ou igual ao número de colunas
// retorna a string cortada com o número máximo de colunas.
        return substr($info, 0, $n_colunas);
    }
}

/**
 * Adiciona a quantidade de espaços informados na String
 * passada na possição informada.
 * 
 * Se a string informada for maior que a quantidade de posições
 * informada, então corta a string para ela ter a quantidade
 * de caracteres exata das posições.
 * 
 * @param string $string String a ter os espaços adicionados.
 * @param int $posicoes Qtde de posições da coluna
 * @param string $onde Onde será adicionar os espaços. I (inicio) ou F (final).
 * @return string
 */
function addEspacos($string, $posicoes, $onde) {

    $aux = strlen($string);

    if ($aux >= $posicoes)
        return substr($string, 0, $posicoes);

    $dif = $posicoes - $aux;

    $espacos = '';

    for ($i = 0; $i < $dif; $i++) {
        $espacos .= ' ';
    }

    if ($onde === 'I')
        return $espacos . $string;
    else
        return $string . $espacos;
}

$txt_cabecalho = array();
$txt_itens = array();
$txt_valor_total = '';
$txt_rodape = array();

$txt_cabecalho[] = '      LANCHONETE DO PAULO - Delivery  ';
$txt_cabecalho[] = 'Rua jose marco n 894 - São Paulo - SP';
$txt_cabecalho[] = 'Fone 016 3822 8899';
$txt_cabecalho[] = ' '; // força pular uma linha entre o cabeçalho e os itens
$txt_cabecalho[] = '---------------------------------------';
$txt_cabecalho[] = '         INFORMAÇÕES DO PEDIDO          ';
$txt_cabecalho[] = ' ';
$txt_cabecalho[] = 'PEDIDO - Nº.: ' . $ped['ped_chave'];
$txt_cabecalho[] = 'DATA-HORA.: ' . Util::format_DD_MM_AAAA_HHMMSS($ped['ped_dataHora']);
$txt_cabecalho[] = 'PAGAMENTO.: ' . $ped['ped_tipoPgto'];
$txt_cabecalho[] = 'VALOR PEDIDO.: R$ ' . number_format($ped['ped_valor'], 2, ',', '.');
$txt_cabecalho[] = 'TAXA DE ENTREGA.: R$ ' . number_format($taxaEntrega, 2, ',', '.');
$txt_cabecalho[] = 'TROCO.: R$ ' . number_format($ped['ped_troco'], 2, ',', '.');
$txt_cabecalho[] = 'OBS.: ' . strtoupper($ped['ped_observ']);
$txt_cabecalho[] = ' ';

if ($ped['ped_forma_de_entrega'] == 'ENTREGAR') {
    $txt_cabecalho[] = '---------------------------------------';
    $txt_cabecalho[] = '         INFORMAÇÕES DA ENTREGA          ';
    $txt_cabecalho[] = ' ';
    $txt_cabecalho[] = 'END.: ' . $cliente['usu_endereco'] . ' Nº.:' . $cliente['usu_numeroEnd'];
    $txt_cabecalho[] = 'REF.: ' . $cliente['usu_pontoRef'];
    $txt_cabecalho[] = 'BAIRRO.: ' . $bairro['bai_nome'];
    $txt_cabecalho[] = 'CONTATO.: ' . $cliente['usu_contato'];
    $txt_cabecalho[] = '---------------------------------------';
}



$txt_cabecalho[] = ' '; // força pular uma linha entre o cabeçalho e os itens
$txt_cabecalho[] = '---------------------------------------';
$txt_cabecalho[] = '         PRODUTOS VENDIDOS          ';
$txt_cabecalho[] = ' ';
$txt_itens[] = array('Cod.', 'Produto', '-', 'Qtd', 'V. UN', 'Total');
$tot_itens = 0;


foreach ($produto as $itens_venda) {

    $txt_itens[] = array($itens_venda['prdcodigo'], strtolower($itens_venda['prddescricao']), '', $itens_venda['quantidade'], $itens_venda['preco'], $itens_venda['total']);
    $tot_itens += $itens_venda['total'];

    foreach ($adicional as $adc) {

        if ($itens_venda['prdcodigo'] == $adc['adcpertenceaoproduto']) {
            $txt_itens[] = array('+++', strtolower($adc['prddescricao']), '', $adc['quantidade'], $adc['preco'], $adc['total']);
            $tot_itens += $adc['total'];
        }
    }
}
$txt_rodape[] = '---------------------------------------';
$sub = $taxaEntrega + $tot_itens;

$aux_valor_total = 'SUBTOTAL.: R$ ' . $sub;

// calcula o total de espaços que deve ser adicionado antes do "Sub-total" para alinhado a esquerda
$total_espacos = $n_colunas - strlen($aux_valor_total);

$espacos = '';

for ($i = 0; $i < $total_espacos; $i++) {
    $espacos .= ' ';
}

$txt_valor_total = $espacos . $aux_valor_total;
$txt_rodape[] = '         INFORMAÇÕES DO CLIENTE          '; // força pular uma linha
$txt_rodape[] = ' '; // força pular uma linha
$txt_rodape[] = 'Cód. Cliente.: ' . $ped['cli_codigo'];
$txt_rodape[] = 'Nome Cliente.: ' . $ped['cli_nome'];
$txt_rodape[] = 'Contato.: ' . $cliente['usu_contato'];
$txt_rodape[] = ' '; // força pular uma linha

$txt_rodape[] = '________________________________________';
$txt_rodape[] = '         Assinatura do Cliente          ';

// centraliza todas as posições do array $txt_cabecalho
$cabecalho = array_map("centraliza", $txt_cabecalho);

/* para cada linha de item (array) existente no array $txt_itens,
 * adiciona cada posição da linha em um novo array $itens
 * fazendo a formatação dos espaçamentos entre cada coluna
 * da linha através da função "addEspacos"
 */

/*
 * Cod. => máximo de 5 colunas
 * Produto => máximo de 11 colunas
 * Env. => máximo de 6 colunas
 * Qtd => máximo de 4 colunas
 * V. UN => máximo de 7 colunas
 * Total => máximo de 7 colunas
 *
 * $itens[] = 'Cod. Produto Env. Qtd V. UN Total'
 */
foreach ($txt_itens as $item) {

    $itens[] = addEspacos($item[0], 5, 'F')
            . addEspacos($item[1], 16, 'F')
            . addEspacos($item[2], 1, 'I')
            . addEspacos($item[3], 4, 'I')
            . addEspacos($item[4], 7, 'I')
            . addEspacos($item[5], 7, 'I');
}

/* concatena o cabelhaço, os itens, o sub-total e rodapé
 * adicionando uma quebra de linha "\r\n" ao final de cada
 * item dos arrays $cabecalho, $itens, $txt_rodape
 */
$txt = implode("\r\n", $cabecalho)
        . "\r\n"
        . implode("\r\n", $itens)
        . "\r\n"
        . $txt_valor_total . "\r\n" . 'OBS.: ' . strtoupper($ped['ped_observ'])// Sub-total
        . "\r\n\r\n"
        . implode("\r\n", $txt_rodape);

// caminho e nome onde o TXT será criado no servidor
$file = 'venda_' . $ped['cli_nome'] . '_' . $ped['ped_chave'] . '.txt';

// cria o arquivo
$_file = fopen($file, "w");
fwrite($_file, $txt);
fclose($_file);

header("Pragma: public");
// Força o header para salvar o arquivo
header("Content-type: application/save");
header("X-Download-Options: noopen "); // For IE8
header("X-Content-Type-Options: nosniff"); // For IE8
// Pré define o nome do arquivo
header("Content-Disposition: attachment; filename=venda.txt");
header("Expires: 0");
header("Pragma: no-cache");

// Lê o arquivo para download
readfile($file);

exit;
