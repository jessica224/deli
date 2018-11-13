<?php

header("Access-Control-Allow-Origin: *");

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Model\UsuarioDao;

require '../../vendor/autoload.php';
require_once '../../config.php';
$app = new \Slim\App();


$app->post('/getLogin', function(Request $request, Response $response) {

    $data = $request->getParsedBody();
    $usu_email = $data['usu_email'];
    $usu_senha = $data['usu_senha'];

    $user = UsuarioDao::getLoginValid($usu_email, $usu_senha);

    if ($user) {
        echo '{"user":' . json_encode($user) . '}';
    } else {
        echo '{"user":{"ID_CPF_EMPRESA":"","usu_codigo":"","usu_nome":"","usu_email":"","usu_senha":"","usu_contato":"","usu_endereco":"","usu_numeroEnd":"","bai_codigo":"","usu_key":"","usu_nivel":""}}';
    }
});

$app->post('/save', function(Request $request, Response $response) {

    $data = $request->getParsedBody();

    if (!isset($data['API_KEY'])) {
        echo '{"cliente":"API_KEY ERROR"}';
        exit();
    }

    $API_KEY = filter_var($data['API_KEY'], FILTER_SANITIZE_STRING);
    if ($API_KEY != API_KEY) {
        echo '{"cliente":"API_KEY ERROR"}';
        exit();
    }


    $array_data = array(
        'ID_CPF_EMPRESA' => filter_var($data['ID_CPF_EMPRESA'], FILTER_SANITIZE_STRING),
        'usu_codigo' => filter_var($data['cli_codigo'], FILTER_SANITIZE_STRING),
        'usu_nome' => filter_var($data['cli_nome'], FILTER_SANITIZE_STRING),
        'usu_email' => filter_var($data['cli_email'], FILTER_SANITIZE_EMAIL),
        'usu_senha' => filter_var($data['cli_senha'], FILTER_SANITIZE_STRING),
        'usu_contato' => filter_var($data['cli_contato'], FILTER_SANITIZE_STRING),
        'usu_endereco' => filter_var($data['cli_endereco'], FILTER_SANITIZE_STRING),
        'usu_numeroEnd' => filter_var($data['cli_numeroEnd'], FILTER_SANITIZE_STRING),
        'usu_pontoRef' => filter_var($data['cli_pontoRef'], FILTER_SANITIZE_STRING),
        'bai_codigo' => filter_var($data['bai_codigo'], FILTER_SANITIZE_NUMBER_INT),
        'usu_key' => filter_var($data['cli_key'], FILTER_SANITIZE_STRING),
        'usu_nivel' => 'CLI'
    );

    $ID_CPF_EMPRESA = $data['ID_CPF_EMPRESA'];
    $usr = UsuarioDao::getUsuario($ID_CPF_EMPRESA);

    if (!$usr) {
        UsuarioDao::insert($array_data, 'usuarios');
        echo '{"cliente":"sucessosave"}';
    } else {
        $arrayCond = array('ID_CPF_EMPRESA=' => $ID_CPF_EMPRESA);
        UsuarioDao::update($array_data, $arrayCond, 'usuarios');
        echo '{"cliente":"sucessoupdate"}';
    }
});
$app->run();

