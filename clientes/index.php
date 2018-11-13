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
    $usuarios = UsuarioDao::getAllClientes();
    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'usuarios' => $usuarios,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Lista Clientes');
    echo $template->render('lista-clientes.twig', $params);
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
