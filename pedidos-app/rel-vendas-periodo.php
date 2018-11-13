<?php

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

if ($_SESSION['usu_nivel'] != "ADM") {
    $mpdf->WriteHTML('apenas administradores podem ver esse relatório');
    $mpdf->Output();
    exit();
}


$data1 = $_POST['data1'];
$data2 = $_POST['data2'];
$imprimeitens = $_POST["IMPRIME_ITENS"];

$cabecalho = "RELATORIO DE VENDAS POR PERIODO : {DATE d/m/Y}   PÁG. Nº [{PAGENO}]";

$vendas = PedidosDao::getPedidosPorPeriodo(Util::format_AAAA_MM_DD($data1), Util::format_AAAA_MM_DD($data2));


if (!$vendas) {
    $mpdf->WriteHTML('nada encontrado neste período');
    $mpdf->Output();
    exit();
}


//imprime a venda sem os itens
if (!$imprimeitens) {
    $html .= '        
            <table style="padding:2px; width:100%;">            
                    <tr>                
                        <td align="center"><img src="../assets/images/delivery_logo.png"  width="80px" heigth="80px"  /></td>
                        <td align="center"  colspan="10" >                    
                            <b align="center" class="cabecalho_venda_titulo">Delivery App</b> <br> 
                            <b align="left">Cidade.:</b>Pernambuco - PE<br>
                            <b align="left">Endereço.:</b>Rua das manga 1525<br>
                            <b>Bairro.:</b>Centro                               
                            <b>CEP.:</b>15.000-999
                            <b>CNPJ.:</b>0005546549848948894<br>                                                   
                            <b style="text-align:center">Tel.: 18 99565456  / 14 38225 668948</b> <br>                               
                            <b align="center">E-mail.:</b> minhaempresa@mail.com<br>
                            <b align="center">Site.:</b> www.meusite.com.br
                        </td>                  
                    </tr>
                </table>

                <table class="tabela" cellspacing="2" cellpadding="2">
                <thead>
                    <tr>
                        <th align=left class="titulos">CodVenda</th>
                        <th align=left class="titulos">Data</th>
                        <th align=left class="titulos">Cliente</th>
                        <th align=left class="titulos">Pagamento</th>
                        <th align=right class="titulos">Valor</th>                       
                    </tr>
                </thead>';
    $html .= '<tbody>';

    $tot = 0;
    foreach ($vendas as $info_venda) {

// pegando a taxa de entrega... preguiça de criar join rss
        $cliente = UsuarioDao::getUsuarioBy_usu_codigo($info_venda['cli_codigo']);
        $bairro = BairrosDao::getBairro($cliente['bai_codigo']);
        $taxaEntrega = 0;
        if ($info_venda['ped_forma_de_entrega'] == 'ENTREGAR') {
            $taxaEntrega = $bairro['bai_taxa'];
        }

        $html .= '<tr>';
        $html .= '<td align=left class="linhas_itens">' . $info_venda['ped_chave'] . '</td>';
        $html .= '<td align=left class="linhas_itens">' . Util::format_DD_MM_AAAA_HHMMSS($info_venda['ped_dataHora']) . '</td>';
        $html .= '<td align=left class="linhas_itens">' . $info_venda['cli_nome'] . '</td>';
        $html .= '<td align=left class="linhas_itens">' . $info_venda['ped_tipoPgto'] . '</td>';
        $html .= '<td align=right class="linhas_itens">' . number_format($info_venda['ped_valor'] + $taxaEntrega, 2, ',', '.') . '</td>';
        $tot = $tot + $info_venda['ped_valor'] + $taxaEntrega;
        $html .= '</tr>';
    }

    $html .= '<tr>';
    $html .= '<td align=right colspan="4" class="subtotal">Total</td>';
    $html .= '<td align=right class="subtotal">' . number_format($tot, 2, ',', '.') . '</td>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '</table>';
}


// imprime a venda com todos os itens
if ($imprimeitens) {

    $html .= '<table style="padding:2px; width:100%;">            
                    <tr>                
                        <td align="center"><img src="../assets/images/delivery_logo.png"  width="80px" heigth="80px"  /></td>
                        <td align="center"  colspan="10" >                    
                            <b align="center" class="cabecalho_venda_titulo">Delivery App</b> <br> 
                            <b align="left">Cidade.:</b>Pernambuco - PE<br>
                            <b align="left">Endereço.:</b>Rua das manga 1525<br>
                            <b>Bairro.:</b>Centro                               
                            <b>CEP.:</b>15.000-999
                            <b>CNPJ.:</b>0005546549848948894<br>                                                   
                            <b style="text-align:center">Tel.: 18 99565456  / 14 38225 668948</b> <br>                               
                            <b align="center">E-mail.:</b> minhaempresa@mail.com<br>
                            <b align="center">Site.:</b> www.meusite.com.br
                        </td>                  
                    </tr>
                </table>
            <br>
            <br>';

    $total_geral = 0;
    foreach ($vendas as $info_venda) {

        $items = PedidosDao::getItemsPedido($info_venda['ped_chave']);

// pegando a taxa de entrega... preguiça de criar join rss
        $cliente = UsuarioDao::getUsuarioBy_usu_codigo($info_venda['cli_codigo']);
        $bairro = BairrosDao::getBairro($cliente['bai_codigo']);
        $taxaEntrega = 0;
        if ($info_venda['ped_forma_de_entrega'] == 'ENTREGAR') {
            $taxaEntrega = $bairro['bai_taxa'];
        }

        $total_geral += number_format($info_venda['ped_valor']) + $taxaEntrega;

        $html .= '<table style="padding:2px; width:100%;">
        <thead>
                <tr>
                  <td valign="top" align="left"><b>Venda.: </b></td>
                  <td colspan="10">' . $info_venda['ped_chave'] . '</td>                  
                </tr>                
                <tr>
                    <td valign="bottom" align="left"><b>Data Venda.: </b></td>
                    <td colspan="3" align="left"> ' . Util::format_DD_MM_AAAA_HHMMSS($info_venda['ped_dataHora']) . '</td>            
                </tr>                
                <tr>
                    <td align="left"><b>Cliente.:</b></td>
                    <td colspan="3"> ' . $info_venda['cli_nome'] . ' </td>                    
                </tr>                
                <tr>
                    <td valign="top" align="left"><b>Valor.: </b></td>
                    <td colspan="10"><b>' . number_format($info_venda['ped_valor'] + $taxaEntrega, 2, ',', '.') . '</b></td>                   
                </tr>          
               
                </thead>
            </table>';
        $html .= '<table class="tabela" cellspacing="2" cellpadding="2">';
        $html .= '<thead>
                <tr>
                <th align=center class="titulos">CodProd</th>
                <th align=left class="titulos">Descrição do Produto</th>
                <th align=left class="titulos">Quant.</th>
                <th align=left class="titulos">Valor Unit.</th>
                <th align=right class="titulos">Total</th>
                </tr>
                </thead>';
        $html .= '<tbody>';

        $tot = 0;
        foreach ($items as $itens_venda) {
            $html .= '<tr>';
            $html .= '<td align=center class="linhas_itens">' . $itens_venda['ped_chave'] . '</td>';
            $html .= '<td align=left class="linhas_itens"> ' . $itens_venda['prddescricao'] . '</td>';
            $html .= '<td align=left class="linhas_itens">' . $itens_venda['quantidade'] . '</td>';
            $html .= '<td align=left class="linhas_itens">' . number_format($itens_venda['preco'], 2, ',', '.') . '</td>';
            $html .= '<td align=right class="linhas_itens">' . number_format($itens_venda['total'], 2, ',', '.') . '</td>';
            $html .= '</tr>';
            $tot += $itens_venda['total'];
        }

        $html .= '<tr>';
        $html .= '<td align=right colspan="4" class="subtotal">Total</td>';
        $html .= '<td align=right class="subtotal">' . number_format($tot + $taxaEntrega, 2, ',', '.') . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td  colspan="18" align="center"><b>___________________________________________________________________</b></td>';
        $html .= '</tr>';

        $html .= '</tbody>';
        $html .= '</table>';
    }
}
if ($imprimeitens) {
    $html .= '<table border=1>';
    $html .= '<tr>';
    $html .= '<td align=right colspan="4" class="subtotal">Total Geral </td>';
    $html .= '<td align=right class="subtotal">' . number_format($total_geral, 2, ',', '.') . '</td>';
    $html .= '</tr>';
    $html .= '</table>';
}


$mpdf->SetDisplayMode('fullwidth');
$styleSheet = file_get_contents('../assets/css/estilo_impressao.css');
$mpdf->SetHeader($cabecalho);
$mpdf->WriteHTML($styleSheet, 1);
$mpdf->WriteHTML($html);
$mpdf->Output();



