<?php

header("Access-Control-Allow-Origin: *");
require '../../vendor/autoload.php';
require_once '../../config.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Model\PedidosDao;

$app = new \Slim\App();

$app->post('/save', function(Request $request, Response $response) {

    $data = $request->getParsedBody();

    if (!isset($data['API_KEY'])) {
        echo '{"cpedido":API_KEY ERROR}';
        exit();
    }

    $API_KEY = filter_var($data['API_KEY'], FILTER_SANITIZE_STRING);
    if ($API_KEY != API_KEY) {
        echo '{"cpedido":API_KEY ERROR}';
        exit();
    }

    date_default_timezone_set('America/Sao_Paulo');

    $array_data = array(
        'ID_CPF_EMPRESA' => filter_var($data['ID_CPF_EMPRESA'], FILTER_SANITIZE_STRING),
        'ped_chave' => filter_var($data['ped_chave'], FILTER_SANITIZE_STRING),
        'ped_valor' => filter_var($data['ped_valor'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        'ped_dataHora' => date("Y-m-d H:i:s"),
        'ped_tipoPgto' => filter_var($data['ped_tipoPgto'], FILTER_SANITIZE_STRING),
        'ped_observ' => filter_var($data['ped_observ'], FILTER_SANITIZE_STRING),
        'ped_forma_de_entrega' => filter_var($data['ped_forma_de_entrega'], FILTER_SANITIZE_STRING),
        'ped_status' => filter_var($data['ped_status'], FILTER_SANITIZE_STRING),
        'cli_codigo' => filter_var($data['cli_codigo'], FILTER_SANITIZE_NUMBER_INT),
        'cli_nome' => filter_var($data['cli_nome'], FILTER_SANITIZE_STRING),
        'ped_troco' => filter_var($data['ped_troco'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)
    );

    $ped_chave = $data['ped_chave'];
    $cped = PedidosDao::getCPedido($ped_chave);

    if (!$cped) {
        PedidosDao::insert($array_data, 'cpedido');
        echo '{"cpedido":' . json_encode($ped_chave) . '}';
    }
});


$app->post('/status', function(Request $request, Response $response) {
    $data = $request->getParsedBody();
    $status = PedidosDao::getStatusPedido($data['ped_chave']);
    echo '{"pedido":' . json_encode($status['ped_status']) . '}';
});

$app->post('/saveItens', function(Request $request, Response $response) {

    $data = $request->getParsedBody();

    if (!isset($data['API_KEY'])) {
        echo '{"dpedido":API_KEY ERROR}';
        exit();
    }

    $API_KEY = filter_var($data['API_KEY'], FILTER_SANITIZE_STRING);
    if ($API_KEY != API_KEY) {
        echo '{"dpedido":API_KEY ERROR}';
        exit();
    }

    $array_data = array(
        'ID_CPF_EMPRESA' => filter_var($data['ID_CPF_EMPRESA'], FILTER_SANITIZE_STRING),
        'pedchave' => filter_var($data['pedchave'], FILTER_SANITIZE_STRING),
        'prdcodigo' => filter_var($data['prdcodigo'], FILTER_SANITIZE_NUMBER_INT),
        'prddescricao' => filter_var($data['prddescricao'], FILTER_SANITIZE_STRING),
        'quantidade' => filter_var($data['quantidade'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        'preco' => filter_var($data['preco'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        'total' => filter_var($data['total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        'tipo' => filter_var($data['tipo'], FILTER_SANITIZE_STRING),
        'adcpertenceaoproduto' => filter_var($data['adcpertenceaoproduto'], FILTER_SANITIZE_NUMBER_INT)
    );
    PedidosDao::insert($array_data, 'dpedido');
    //echo '{"dpedido":' . json_encode($produto) . '}';
});


$app->run();
