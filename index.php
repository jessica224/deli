<?php

ob_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Access-Control-Allow-Origin: *");
require 'vendor/autoload.php';
require_once './config.php';

use App\Util\Util;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$load_folder_view = new Twig_Loader_Filesystem('view');
$template = new Twig_Environment($load_folder_view, array());
$configuration = ['settings' => ['displayErrorDetails' => true,],];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

$app->get('/', function () {
    global $template;
    $params = array(
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Delivery App');
    echo $template->render('login.twig', $params);
});

$app->get('/admin', function () {
    redirect_login();
    global $template;
    $EMPRESA_OPERACAO = file_get_contents('empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Área Administrativa');
    echo $template->render('admin.twig', $params);
});

$app->get('/status', function(Request $request, Response $response) {
    echo '{"status":' . json_encode(file_get_contents('empresa.txt')) . '}';
});


$app->get('/update', function () {
    redirect_login();
    $valor = file_get_contents('empresa.txt');
    if ($valor == "SIM")
        $chave = "NAO";
    else
        $chave = "SIM";
    $arquivo = fopen('empresa.txt', 'w+');
    fwrite($arquivo, $chave);
    fclose($arquivo);
    echo file_get_contents('empresa.txt');
});


$app->post('/login', function(Request $req, Response $res, $args = []) {
    $data = $req->getParsedBody();
    $usu_email = filter_var($data['usu_email'], FILTER_SANITIZE_EMAIL);
    $usu_senha = filter_var($data['usu_senha'], FILTER_SANITIZE_STRING);

    ob_start();
    session_start();

    // Constante com a quantidade de tentativas aceitas
    define('TENTATIVAS_ACEITAS', 5);
    // Constante com a quantidade minutos para bloqueio
    define('MINUTOS_BLOQUEIO', 30);

    //Verifica se a origem da requisição é do mesmo domínio da aplicação
//    if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != "http://desenvolvimentodesis.tempsite.ws/"):
//        echo 1;
 //       exit();
 //   endif;

    if (empty($usu_email)):
        echo 1;
        exit();
    endif;

    if (empty($usu_senha)):
        echo 1;
        exit();
    endif;

    // Verifica se o formato do e-mail é válido
    if (!filter_var($usu_email, FILTER_VALIDATE_EMAIL)):
        echo 1;
        exit();
    endif;


    // Dica 4 - Verifica se o usuário já excedeu a quantidade de tentativas erradas do dia
    $tentativas_usuario = new App\Model\UsuarioDao();
    $tentativa = $tentativas_usuario->getTentativas();
    if (!empty($tentativa['tentativas']) && intval($tentativa['minutos']) <= MINUTOS_BLOQUEIO):
        $_SESSION["tentativas"] = '';
        echo 3;
        exit();
    endif;


    $usuario = new App\Model\UsuarioDao();
    $retorno = $usuario->login($usu_email, $usu_senha);

    if (is_null($retorno["ID_CPF_EMPRESA"])) {
        $_SESSION['tentativas'] = (isset($_SESSION['tentativas'])) ? $_SESSION['tentativas'] += 1 : 1;
        $bloqueado = ($_SESSION['tentativas'] == TENTATIVAS_ACEITAS) ? 'SIM' : 'NAO';
        $usuario->insertTentativa($usu_email, $usu_senha, $bloqueado);
        echo 1;
        exit();
    } else {
        //iniciando a session depois do login
        sleep(1);
        date_default_timezone_set('America/Sao_Paulo');
        $_SESSION["ultimoAcesso"] = date("Y-n-j H:i:s");
        $_SESSION["TEMPO_DA_SESSAO"] = 2000;
        $_SESSION['ID_CPF_EMPRESA'] = $retorno["ID_CPF_EMPRESA"];
        $_SESSION['ID'] = $retorno["ID"];
        $_SESSION['usu_codigo'] = $retorno["usu_codigo"];
        $_SESSION['usu_email'] = $retorno["usu_email"];
        $_SESSION['usu_nivel'] = $retorno["usu_nivel"];
        echo 2;
    }
});
$app->get('/logoff', function () {
    global $template;
    session_start();
    session_destroy();
    $params = array(
        'BASE_URL' => BASE_URL);
    echo $template->render('login.twig', $params);
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
