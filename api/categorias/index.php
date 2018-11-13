<?php
header("Access-Control-Allow-Origin: *");
require '../../vendor/autoload.php';

$app = new \Slim\App();

use App\Model\CategoriaDao;


$app->get('/get', function() {   
    echo '{"categorias":' . json_encode(CategoriaDao::getCategorias()) . '}';
});

$app->run();
