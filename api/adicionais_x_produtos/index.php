<?php

header("Access-Control-Allow-Origin: *");
require '../../vendor/autoload.php';
require_once '../../config.php';

$app = new \Slim\App();

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Model\Adicionais_X_Produtos;

$app->post('/getAdicionaisProduto', function(Request $request, Response $response) {
    $data = $request->getParsedBody();
    $PRD_CODIGO = filter_var($data['PRD_CODIGO'], FILTER_SANITIZE_STRING);
    $API_KEY = filter_var($data['API_KEY'], FILTER_SANITIZE_STRING);
    if ($API_KEY == API_KEY)
        echo '{"adicionais_prod":' . json_encode(Adicionais_X_Produtos::getAdicionaisProduto($PRD_CODIGO)) . '}';
    else
        echo '{"adicionais_prod":null}';
});



$app->run();
