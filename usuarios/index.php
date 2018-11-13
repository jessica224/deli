<?php

ob_start();
error_reporting(E_ALL ^ E_NOTICE);

require_once '../vendor/autoload.php';
require_once '../config.php';

use App\Util\Util;
use App\Model\UsuarioDao;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$load_folder_view = new Twig_Loader_Filesystem('../view');
$template = new Twig_Environment($load_folder_view, array());
$configuration = ['settings' => ['displayErrorDetails' => true,],];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

$app->get('/listar', function () {
    redirect_login();
    global $template;
    $usuarios = UsuarioDao::getAllUsuarios();
    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'usuarios' => $usuarios,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Lista de Usuários');
    echo $template->render('lista-usuarios.twig', $params);
});

$app->get('/editar/{id}', function (Request $req, Response $res, $args = []) {
    redirect_login();
    global $template;
    $usu_codigo = UsuarioDao::getUsuarioBy_ID($args['id']);
    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'ID_CPF_EMPRESA' => $_SESSION['ID_CPF_EMPRESA'],
        'usuario' => $usu_codigo,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Editar Usuários');
    echo $template->render('form-usuarios.twig', $params);
});

$app->get('/novo', function () {
    redirect_login();
    global $template;
    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'ID_CPF_EMPRESA' => $_SESSION['ID_CPF_EMPRESA'],
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Cadastro de Usuários');
    echo $template->render('form-usuarios.twig', $params);
});


$app->get('/atualizar', function () {
    redirect_login();
    global $template;

    $usu_codigo = UsuarioDao::getUsuarioBy_ID($_SESSION['ID']);

    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'ID_CPF_EMPRESA' => $_SESSION['ID_CPF_EMPRESA'],
        'usuario' => $usu_codigo,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Editar Usuários');
    echo $template->render('form-usuarios.twig', $params);
});

$app->get('/excluir/{id}', function (Request $req, Response $res, $args = []) {
    redirect_login();
    global $template;
    $cat_codigo = (int) $args['id'];

    // nao pode excluir se tiver apenas 1 ADM
    $users = UsuarioDao::getAllUsuarios();
    if (count($users) == 1 && $users[0]['usu_nivel'] == "ADM") {
        return $res->withStatus(302)->withHeader('Location', BASE_URL . '/usuarios/listar');
        exit();
    }

    $arrayCondicao = array('ID=' => $cat_codigo);
    UsuarioDao::delete($arrayCondicao, 'usuarios');
    return $res->withStatus(302)->withHeader('Location', BASE_URL . '/usuarios/listar');
});

$app->post('/salvar', function(Request $request, Response $response) {
    redirect_login();

    $data = $request->getParsedBody();
    $arrayUser = array(
        'ID_CPF_EMPRESA' => $data['ID_CPF_EMPRESA'],
        'usu_codigo' => mt_rand(),
        'usu_nome' => $data['usu_nome'],
        'usu_email' => $data['usu_email'],
        'usu_senha' => $data['usu_senha'],
        'usu_contato' => $data['usu_contato'],
        'usu_endereco' => $data['usu_endereco'],
        'usu_numeroEnd' => $data['usu_numeroEnd'],
        'bai_codigo' => $data['bai_codigo'],
        'usu_key' => $data['usu_key'],
        'usu_nivel' => $data['usu_nivel']
    );

    if ($data['ID'] > 0) {
        $arrayCondicao = array('ID=' => $data['ID']);
        UsuarioDao::update($arrayUser, $arrayCondicao, 'usuarios');
    } else {
        UsuarioDao::insert($arrayUser, 'usuarios');
    }
    return $response->withStatus(302)->withHeader('Location', BASE_URL . '/usuarios/listar');
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
