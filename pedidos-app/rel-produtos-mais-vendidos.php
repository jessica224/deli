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

$cabecalho = "RELATORIO DE PRODUTOS MAIS VENDIDOS : {DATE d/m/Y}   PÁG. Nº [{PAGENO}]";

$vendas = PedidosDao::getProdutosMaisVendidos(Util::format_AAAA_MM_DD($data1), Util::format_AAAA_MM_DD($data2));

$html .= '<span class="pedido"> Período .: ' . $data1 . '  À  ' . $data2 . ' *** ' . Util::retorna_diferenca_2_datas($data1, $data2) . ' dias <br/></span>';
$html .= '<br>';
$html .= '<br>';
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
                        <th align=left class="titulos">Código</th>
                        <th align=left class="titulos">Descrição</th>
                        <th align=center class="titulos"> Quantidade Vendida</th>                        
                    </tr>
                </thead>';
$html .= '<tbody>';

foreach ($vendas as $ven) {
    $html .= '<tr>';
    $html .= '<td  align=left  class="linhas_itens" >' . $ven['cod'] . ' </td> ';
    $html .= '<td  align=left  class="linhas_itens" >' . $ven['descr'] . '</td>';
    $html .= '<td  align=center  class="linhas_itens" >' . number_format($ven['qtd'], 0, ",", ".") . '</td> ';
    $html .= '</tr>';
}
$html .= '</thead>';
$html .= '</table>';

$mpdf->SetDisplayMode('fullwidth');
$styleSheet = file_get_contents('../assets/css/estilo_impressao.css');
$mpdf->SetHeader($cabecalho);
$mpdf->WriteHTML($styleSheet, 1);
$mpdf->WriteHTML($html);
$mpdf->Output();




