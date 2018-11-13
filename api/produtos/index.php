<?php

header("Access-Control-Allow-Origin: *");
require '../../vendor/autoload.php';
require_once '../../config.php';

use App\Model\ProdutoDao;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App();
$app->get('/get', 'getAll');

function getAll() {
    echo '{"produtos":' . json_encode(ProdutoDao::getProdutos()) . '}';
}

$app->post('/getProdutosCategoria', function(Request $request, Response $response) {
    $data = $request->getParsedBody();
    $CAT_CODIGO = filter_var($data['CAT_CODIGO'], FILTER_SANITIZE_STRING);
    $API_KEY = filter_var($data['API_KEY'], FILTER_SANITIZE_STRING);
    if ($API_KEY == API_KEY)
        echo '{"produtos_cat":' . json_encode(ProdutoDao::getProdutosCategoria($CAT_CODIGO)) . '}';
    else
        echo '{"produtos_cat":null}';
});
$app->run();
