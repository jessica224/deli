<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Model\PedidosDao;

require '../../vendor/autoload.php';

$app = new \Slim\App();
$app->post('/save', 'save');

function save(Request $req, Response $res, $args = []) {
    $data = $req->getParsedBody();
    $std = new stdClass();
    $std->ID_CPF_EMPRESA = $data['ID_CPF_EMPRESA'];
    $std->ped_chave = $data['ped_chave'];
    $std->itn_ped_nro_item = $data['itn_ped_nro_item'];
    $std->itn_prd_codigo = $data['itn_prd_codigo'];
    $std->itn_prd_descricao = $data['itn_prd_descricao'];
    $std->itn_quantidade = $data['itn_quantidade'];
    $std->itn_preco = $data['itn_preco'];
    $std->itn_total = $data['itn_total'];
    echo PedidosDao::salvarItens($std);
}

$app->run();
