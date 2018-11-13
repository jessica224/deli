<?php

ob_start();
error_reporting(E_ALL ^ E_NOTICE);

require '../vendor/autoload.php';
require_once '../config.php';

use App\Util\Util;
use App\Model\AdicionaisDao;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\UploadedFile;
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
        'TITLE' => 'Cadastro Adicionais do Produto');
    echo $template->render('form-adicionais.twig', $params);
});

$app->get('/editar/{id}', function (Request $req, Response $res, $args = []) {
    redirect_login();
    global $template;

    $adicional = AdicionaisDao::getAdicional($args['id']);
    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'ID_CPF_EMPRESA' => $_SESSION['ID_CPF_EMPRESA'],
        'adicional' => $adicional,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Editar Adicionais do Produto');
    echo $template->render('form-adicionais.twig', $params);
});

$app->get('/listar', function () {
    redirect_login();
    global $template;
    $adicionais = AdicionaisDao::getAdicionais();
    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'adicionais' => $adicionais,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Lista de Adicionais de Produtos');
    echo $template->render('lista-adicionais.twig', $params);
});

$app->post('/salvar', function(Request $request, Response $response) {
    redirect_login();
    $data = $request->getParsedBody();

    $dataArray = array(
        'ID_CPF_EMPRESA' => filter_var($data['ID_CPF_EMPRESA'], FILTER_SANITIZE_STRING),
        'adc_descricao' => filter_var($data['adc_descricao'], FILTER_SANITIZE_STRING),
        'adc_img' => 'sem_imagem'
    );

    if ($data['adc_codigo'] <= 0) {
        AdicionaisDao::insert($dataArray, 'adicionais');
    } else {
        $arrayCondicao = array('adc_codigo=' => $data['adc_codigo']);
        AdicionaisDao::update($dataArray, $arrayCondicao, 'adicionais');
    }
    return $response->withStatus(302)->withHeader('Location', BASE_URL . '/adicionais/listar');
});

$app->get('/excluir/{id}', function (Request $req, Response $res, $args = []) {
    redirect_login();
    global $template, $log;
    $adc_codigo = (int) $args['id'];
    $arrayCondicao = array('adc_codigo=' => $adc_codigo);
    AdicionaisDao::delete($arrayCondicao, 'adicionais');
    return $res->withStatus(302)->withHeader('Location', BASE_URL . '/adicionais/listar');
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
