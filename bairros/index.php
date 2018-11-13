<?php

ob_start();
error_reporting(E_ALL ^ E_NOTICE);

require_once '../vendor/autoload.php';
require_once '../config.php';

use App\Util\Util;
use App\Model\BairrosDao;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('delivery');
$log->pushHandler(new StreamHandler('log.log', Logger::WARNING));

$load_folder_view = new Twig_Loader_Filesystem('../view');
$template = new Twig_Environment($load_folder_view, array());
$configuration = ['settings' => ['displayErrorDetails' => true,],];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

$app->get('/novo', function () {
    redirect_login();
    global $template;
    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'ID_CPF_EMPRESA' => $_SESSION['ID_CPF_EMPRESA'],
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Cadastro de Bairros');
    echo $template->render('form-bairros.twig', $params);
});

$app->get('/editar/{id}', function (Request $req, Response $res, $args = []) {
    redirect_login();
    global $template;
    $bairro = BairrosDao::getBairro($args['id']);
    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'ID_CPF_EMPRESA' => $_SESSION['ID_CPF_EMPRESA'],
        'bairro' => $bairro,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Editar Bairro');
    echo $template->render('form-bairros.twig', $params);
});

$app->get('/listar', function () {
    redirect_login();
    global $template;
    $bairros = BairrosDao::getBairros();
    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'bairros' => $bairros,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Lista de Bairros');
    echo $template->render('lista-bairros.twig', $params);
});

$app->post('/salvar', function(Request $request, Response $response) {
    redirect_login();
    $data = $request->getParsedBody();
    $std = new stdClass();
    $std->ID_CPF_EMPRESA = filter_var($data['ID_CPF_EMPRESA'], FILTER_SANITIZE_STRING);
    $std->bai_nome = filter_var($data['bai_nome'], FILTER_SANITIZE_STRING);
    $std->bai_taxa = filter_var($data['bai_taxa'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    BairrosDao::salvar($std);
    return $response->withStatus(302)->withHeader('Location', BASE_URL . '/bairros/listar');
});

$app->post('/editar', function(Request $request, Response $response) {
    redirect_login();
    $data = $request->getParsedBody();
    $std = new stdClass();
    $std->bai_codigo = filter_var($data['bai_codigo'], FILTER_SANITIZE_NUMBER_INT);
    $std->ID_CPF_EMPRESA = filter_var($data['ID_CPF_EMPRESA'], FILTER_SANITIZE_STRING);
    $std->bai_nome = filter_var($data['bai_nome'], FILTER_SANITIZE_STRING);
    $std->bai_taxa = filter_var($data['bai_taxa'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    BairrosDao::editar($std);
    return $response->withStatus(302)->withHeader('Location', BASE_URL . '/bairros/listar');
});

$app->get('/excluir/{id}', function (Request $req, Response $res, $args = []) {
    redirect_login();
    global $template;
    $bai_codigo = (int) $args['id'];
    BairrosDao::delete($bai_codigo);
    return $res->withStatus(302)->withHeader('Location', BASE_URL . '/bairros/listar');
});

function redirect_login() {
    global $template;
    if (!Util::sessao_existe()) {
        $params = array(
            'BASE_URL' => BASE_URL,
            'TITLE' => 'Delivery App');
        echo $template->render('login.twig', $params);
        exit();
    }
}

$app->run();
