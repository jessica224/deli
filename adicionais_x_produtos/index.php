<?php

ob_start();
error_reporting(E_ALL ^ E_NOTICE);

require '../vendor/autoload.php';
require_once '../config.php';

use App\Util\Util;
use App\Model\ProdutoDao;
use App\Model\AdicionaisDao;
use App\Model\Adicionais_X_Produtos;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$load_folder_view = new Twig_Loader_Filesystem('../view');
$template = new Twig_Environment($load_folder_view, array());
$configuration = ['settings' => ['displayErrorDetails' => true,],];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

$app->get('/novo', function () {
    redirect_login();
    global $template;
    $adicionais = AdicionaisDao::getAdicionais();
    $produtos = ProdutoDao::getProdutos();
    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'ID_CPF_EMPRESA' => $_SESSION['ID_CPF_EMPRESA'],
        'adicionais' => $adicionais,
        'produtos' => $produtos,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Cadastro de Adicionais Produtos');
    echo $template->render('form-adicional-x-produto.twig', $params);
});

$app->get('/editar/{id}', function (Request $req, Response $res, $args = []) {
    redirect_login();
    global $template;
    $adcxprod = Adicionais_X_Produtos::getAdicionais_x_Produtos_id($args['id']);
    $adicionais = AdicionaisDao::getAdicionais();
    $produtos = ProdutoDao::getProdutos();
    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'ID_CPF_EMPRESA' => $_SESSION['ID_CPF_EMPRESA'],
        'adicionais' => $adicionais,
        'produtos' => $produtos,
        'adcxprod' => $adcxprod,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Editar Adicionais Produto');
    echo $template->render('form-adicional-x-produto.twig', $params);
});

$app->get('/listar', function () {
    redirect_login();
    global $template;
    $adcxprod = Adicionais_X_Produtos::getAdicionais_x_Produtos();
    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'adcxprod' => $adcxprod,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Lista de Adicionais Produtos');
    echo $template->render('lista-adicional-x-produto.twig', $params);
});

$app->post('/salvar', function(Request $request, Response $response) {
    redirect_login();
    $data = $request->getParsedBody();

    $adicional = $data['adc_codigo'];
    $prd = $data['prd_codigo'];

    foreach ($adicional as $adc) {

        $adic = Adicionais_X_Produtos::getVerificaAdicionalCadastrado($adc, $prd);

        if (!$adic) {
            $std = new stdClass();
            $std->ID_CPF_EMPRESA = filter_var($data['ID_CPF_EMPRESA'], FILTER_SANITIZE_STRING);
            $std->prd_codigo = filter_var($data['prd_codigo'], FILTER_SANITIZE_NUMBER_INT);
            $std->adc_codigo = $adc;
            Adicionais_X_Produtos::salvar($std);
        }
    }

    return $response->withStatus(302)->withHeader('Location', BASE_URL . '/adicionais_x_produtos/listar');
});


$app->get('/excluir/{id}', function (Request $req, Response $res, $args = []) {
    redirect_login();
    global $template;
    $id = (int) $args['id'];
    Adicionais_X_Produtos::delete($id);
    return $res->withStatus(302)->withHeader('Location', BASE_URL . '/adicionais_x_produtos/listar');
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
