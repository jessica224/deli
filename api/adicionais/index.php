<?php

header("Access-Control-Allow-Origin: *");
require '../../vendor/autoload.php';
require_once '../../config.php';

$app = new \Slim\App();

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Model\AdicionaisDao;

$app->get('/get', 'getAll');

function getAll() {
    echo '{"adicionais":' . json_encode(AdicionaisDao::getAdicionais()) . '}';
}
$app->run();
