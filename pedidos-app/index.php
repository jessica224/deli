<?php

ob_start();
error_reporting(E_ALL ^ E_NOTICE);

require '../vendor/autoload.php';
require_once '../config.php';

use App\Util\Util;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Model\PedidosDao;
use App\Model\UsuarioDao;
use Mpdf\Mpdf;

$load_folder_view = new Twig_Loader_Filesystem('../view');
$template = new Twig_Environment($load_folder_view, array());
$configuration = ['settings' => ['displayErrorDetails' => true,],];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);


$app->get('/pedidos/{status}', function (Request $req, Response $res, $args = []) {
    redirect_login();
    global $template;
    $pedidos = PedidosDao::getAllPedidos($args['status']);
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'BASE_URL' => BASE_URL,
        'pedidos' => $pedidos,
        'TITLE' => 'Pedidos App');
    echo $template->render('pedidos.twig', $params);
});


$app->get('/pedidos', function () {
    redirect_login();
    global $template;

    //default = 'PEDIDO ENVIADO'
    $pedidos = PedidosDao::getAllPedidos('PEDIDO ENVIADO');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'BASE_URL' => BASE_URL,
        'pedidos' => $pedidos,
        'TITLE' => 'Pedidos App');
    echo $template->render('pedidos.twig', $params);
});



$app->get('/recusar_pedido/{id}', function (Request $req, Response $res, $args = []) {
    redirect_login();
    global $template;


    //atualizando o status do pedido
    $arrayUser = array('ped_status' => 'PEDIDO RECUSADO');
    $arrayCond = array('ped_chave=' => $args['id']);
    PedidosDao::update($arrayUser, $arrayCond, 'cpedido');


    //default = 'PEDIDO ENVIADO'
    $pedidos = PedidosDao::getAllPedidos('PEDIDO ENVIADO');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'BASE_URL' => BASE_URL,
        'pedidos' => $pedidos,
        'TITLE' => 'Pedidos App');
    echo $template->render('pedidos.twig', $params);
});

$app->get('/pedido_aceito/{id}', function (Request $req, Response $res, $args = []) {
    redirect_login();
    global $template;

    //atualizando o status do pedido
    $arrayUser = array('ped_status' => 'PEDIDO ACEITO');
    $arrayCond = array('ped_chave=' => $args['id']);
    PedidosDao::update($arrayUser, $arrayCond, 'cpedido');

    //default = 'PEDIDO ENVIADO'
    $pedidos = PedidosDao::getAllPedidos('PEDIDO ENVIADO');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'BASE_URL' => BASE_URL,
        'pedidos' => $pedidos,
        'TITLE' => 'Pedidos App');
    echo $template->render('pedidos.twig', $params);
});



$app->get('/pedido_entregando/{id}', function (Request $req, Response $res, $args = []) {
    redirect_login();
    global $template;

    //atualizando o status do pedido
    $arrayUser = array('ped_status' => 'ENTREGANDO');
    $arrayCond = array('ped_chave=' => $args['id']);
    PedidosDao::update($arrayUser, $arrayCond, 'cpedido');

    //default = 'PEDIDO ENVIADO'
    $pedidos = PedidosDao::getAllPedidos('PEDIDO ENVIADO');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'BASE_URL' => BASE_URL,
        'pedidos' => $pedidos,
        'TITLE' => 'Pedidos App');
    echo $template->render('pedidos.twig', $params);
});



$app->get('/relvendasperiodo', function () {
    redirect_login();
    global $template;
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Relatório de vendas por período');
    echo $template->render('form-rel-vendas-periodo.twig', $params);
});

$app->get('/relprdmaisvendidos', function () {
    redirect_login();
    global $template;
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Relatório de vendas por período');
    echo $template->render('form-rel-produtos-mais-vendidos.twig', $params);
});

$app->get('/relvendascliente', function () {
    redirect_login();
    $usuarios = UsuarioDao::getAllClientes();
    global $template;
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'usuarios' => $usuarios,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Relatório de vendas por cliente');
    echo $template->render('lista-clientes-relat.twig', $params);
});

function redirect_login() {
    global $template, $log;
    if (!Util::sessao_existe()) {
        $params = array(
            'BASE_URL' => BASE_URL,
            'TITLE' => 'Delivery App');
        echo $template->render('login.twig', $params);
        exit();
    }
}

$app->run();
