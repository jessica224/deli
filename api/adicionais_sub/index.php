<?php

header("Access-Control-Allow-Origin: *");
require '../../vendor/autoload.php';
require_once '../../config.php';

$app = new \Slim\App();

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Model\SubAdicionaisDao;

$app->post('/getSub_Adicionais', function(Request $request, Response $response) {
    $data = $request->getParsedBody();
    $ADC_CODIGO = filter_var($data['ADC_CODIGO'], FILTER_SANITIZE_STRING);
    $API_KEY = filter_var($data['API_KEY'], FILTER_SANITIZE_STRING);
    if ($API_KEY == API_KEY)
        echo '{"subadicionais":' . json_encode(SubAdicionaisDao::getSubAdicionais_Adicionais($ADC_CODIGO)) . '}';
    else
        echo '{"subadicionais":null}';
});



$app->run();
