<?php

header("Access-Control-Allow-Origin: *");
require '../../vendor/autoload.php';

$app = new \Slim\App();
use App\Model\BairrosDao;

$app->get('/getBairros', function() {
    echo '{"bairros":' . json_encode(BairrosDao::getBairros()) . '}';
});
$app->run();
