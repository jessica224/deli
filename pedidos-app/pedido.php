<?php

ob_start();
error_reporting(E_ALL ^ E_NOTICE);
require_once '../vendor/autoload.php';
require_once '../App/Util/Util.php';

use App\Model\PedidosDao;
use App\Model\UsuarioDao;
use App\Model\BairrosDao;
use App\Util\Util;

$mpdf = new \Mpdf\Mpdf();

redirect_login();

function redirect_login() {
    global $template, $mpdf;
    if (!Util::sessao_existe()) {
        $mpdf->WriteHTML('impossivel o acesso');
        $mpdf->Output();
        exit();
    }
}

$cabecalho = "DATA IMPRESSÃO : {DATE d/m/Y}   PÁG. Nº [{PAGENO}]";

$ped = PedidosDao::getCPedido($_REQUEST["ped_chave"]);
$produto = PedidosDao::getProdutoPedido($_REQUEST["ped_chave"]);
$adicional = PedidosDao::getAdicionalPedido($_REQUEST["ped_chave"]);

if (!$ped) {
    $mpdf->WriteHTML('pedido não existe');
    $mpdf->Output();
    exit();
}

$cliente = UsuarioDao::getUsuarioBy_usu_codigo($ped['cli_codigo']);
$bairro = BairrosDao::getBairro($cliente['bai_codigo']);

$taxaEntrega = 0;
if ($ped['ped_forma_de_entrega'] == 'ENTREGAR') {
    $taxaEntrega = $bairro['bai_taxa'];
}



$html = '<table class="tabela" cellspacing="2" cellpadding="2">
         
            <thead>
                
                <tr>
                  <td class="linhaTDCabecalho" width="22%" align="left"><b>PEDIDO.: </b></td>
                  <td class="linhaTDCabecalho">' . $ped['ped_chave'] . '  <b>Data Hora</b>.:' . App\Util\Util::format_DD_MM_AAAA_HHMMSS($ped['ped_dataHora']) . '</td>    
                </tr>                
                
                <tr>
                    <td class="linhaTDCabecalho" align="left"><b>Forma Entrega.: </b></td>
                    <td class="linhaTDCabecalho" colspan="17" align="left"> ' . $ped['ped_forma_de_entrega'] . '</td>            
                </tr>                
                <tr>
                    <td class="linhaTDCabecalho" align="left"><b>Forma Pagamento.: </b></td>
                    <td class="linhaTDCabecalho" colspan="17" align="left"> ' . $ped['ped_tipoPgto'] . '</td>     
                </tr> 
                
                <tr>
                    <td class="linhaTDCabecalho" align="left"><b>Valor.: </b></td>
                    <td class="linhaTDCabecalho" colspan="17" align="left"> ' . number_format($ped['ped_valor'], 2, ',', '.') . '</td>            
                </tr>            

                <tr>         
                    <td class="linhaTDCabecalho" align="left"><b>Taxa de Entrega.:</b></td>
                    <td class="linhaTDCabecalho" colspan="17"> ' . number_format($taxaEntrega, 2, ',', '.') . '</td>       
                </tr> 
                
                <tr>         
                    <td class="linhaTDCabecalho" align="left"><b>Troco.:</b></td>
                    <td class="linhaTDCabecalho" colspan="17"> ' . $ped['ped_troco'] . '</td>       
                </tr> 

                <tr>
                <td  colspan="18" align="center"><b>INFORMAÇÕES DO CLIENTE</b></td>
                </tr>
                <tr>
                    <td class="linhaTDCabecalho" align="left"><b>Cliente.:</b></td>
                    <td class="linhaTDCabecalho" colspan="17"> ' . $cliente['usu_nome'] . ' -  <b>CPF.: </b>' . $cliente['ID_CPF_EMPRESA'] . ' </td>
                    
                </tr>   
                 <tr>
                    <td align="left"><b>Contato.:</b></td>
                    <td colspan="17"> ' . $cliente['usu_contato'] . ' - <b>Email.: </b>' . $cliente['usu_email'] . ' </td>                    
                </tr>  
                <tr>
                    <td class="linhaTDCabecalho" align="left"><b>Endereço.:</b></td>
                    <td class="linhaTDCabecalho" colspan="17"> ' . $cliente['usu_endereco'] . ' Nº.:' . $cliente['usu_numeroEnd'] . ' - <b>Ponto Refer.: </b>' . $cliente['usu_pontoRef'] . ' </td>                    
                </tr>
                
                 <tr>
                    <td class="linhaTDCabecalho" align="left"><b>Bairro.:</b></td>
                    <td class="linhaTDCabecalho" colspan="17"> ' . $bairro['bai_nome'] . ' </td>                    
                </tr>
                
                <tr>
                    <td class="linhaTDCabecalho" align="left"><b>Observação.: </b></td>
                    <td class="linhaTDCabecalho" colspan="17"><b>' . $ped['ped_observ'] . '</b></td>                   
                </tr>
                </thead>
            </table>';

$html .= '</br>';
$html .= '</br>';


$html .= '<table class="tabela" cellspacing="2" cellpadding="2">';
$html .= '<thead>
                <tr>
                <th align=center class="titulos">Item</th>
                <th align=center class="titulos">CodProd</th>
                <th align=left class="titulos">Descrição do Produto</th>
                <th align=left class="titulos">Quant.</th>
                <th align=left class="titulos">Valor Unit.</th>
                <th align=right class="titulos">Total</th>
                </tr>
                </thead>';
$html .= '<tbody>';

$totprod = 0;
$totadc = 0;
$item = 1;
foreach ($produto as $prod) {
    $html .= '<tr>';
    $html .= '<td align=center class="linhas_itens">' . $item . '</td>';
    $html .= '<td align=center class="linhas_itens">' . $prod['prdcodigo'] . '</td>';
    $html .= '<td align=left class="linhas_itens">' . $prod['prddescricao'] . '</td>';
    $html .= '<td align=left class="linhas_itens">' . $prod['quantidade'] . '</td>';
    $html .= '<td align=left class="linhas_itens">' . $prod['preco'] . '</td>';
    $html .= '<td align=right class="linhas_itens">' . $prod['total'] . '</td>';
    $html .= '</tr>';
    $totprod += $prod['total'];

    foreach ($adicional as $adc) {

        if ($prod['prdcodigo'] == $adc['adcpertenceaoproduto']) {
            $html .= '<tr>';
            $html .= '<td class="linhas_subitens" align=center >*</td>';
            $html .= '<td class="linhas_subitens" align=center >' . $adc['prdcodigo'] . '</td>';
            $html .= '<td class="linhas_subitens" align=left > ' . $adc['prddescricao'] . '</td>';
            $html .= '<td class="linhas_subitens" align=left >' . $adc['quantidade'] . '</td>';
            $html .= '<td class="linhas_subitens" align=left >' . $adc['preco'] . '</td>';
            $html .= '<td class="linhas_subitens" align=right >' . $adc['total'] . '</td>';
            $html .= '</tr>';
            $totadc += $adc['total'];
        }
    }
    $item++;
}
$tot = $totadc + $totprod;
$html .= '<tr>';
$html .= '<td align=right colspan="5" class="subtotal">Total</td>';
$html .= '<td align=right class="subtotal">' . number_format($tot, 2, ',', '.') . '</td>';
$html .= '</tr>';

if ($ped['ped_forma_de_entrega'] == 'ENTREGAR') {
    $html .= '<tr>';
    $html .= '<td align=right colspan="5" class="subtotal">Taxa Entrega</td>';
    $html .= '<td align=right class="subtotal">' . number_format($taxaEntrega, 2, ',', '.') . '</td>';
    $html .= '</tr>';
}

$html .= '<tr  style=border:1px dashed green;>';
$html .= '<td align=right colspan="5" class="subtotal">Total a Pagar</td>';
$html .= '<td align=right class="subtotal">' . number_format($taxaEntrega + $tot, 2, ',', '.') . '</td>';
$html .= '</tr>';


$html .= '</tbody>';
$html .= '</table>';


$html .= '</br>';
$html .= '</br>';
$html .= '</br>';
$html .= '</br>';

$html2 .= '<table border=1>';
$html2 .= '<tr>';
$html2 .= '<td></td>';
$html2 .= '</tr>';
$html2 .= '</table>';



$mpdf->SetDisplayMode('fullwidth');
$styleSheet = file_get_contents('../assets/css/estilo_impressao.css');
$mpdf->SetHeader($cabecalho);
$mpdf->WriteHTML($styleSheet, 1);
$mpdf->WriteHTML($html);
$mpdf->Output();

