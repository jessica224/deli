<?php

ob_start();
error_reporting(E_ALL ^ E_NOTICE);

require '../vendor/autoload.php';
require_once '../config.php';

use App\Util\Util;
use App\Model\ProdutoDao;
use App\Model\AdicionaisDao;
use App\Model\SubAdicionaisDao;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\UploadedFile;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('delivery');
$log->pushHandler(new StreamHandler('log.log', Logger::WARNING));

$load_folder_view = new Twig_Loader_Filesystem('../view');
$template = new Twig_Environment($load_folder_view, array('debug' => true));
$configuration = ['settings' => ['displayErrorDetails' => true]];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

$app->get('/novo', function () {
    redirect_login();
    global $template;
    $adicionais = AdicionaisDao::getAdicionais();
    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'ID_CPF_EMPRESA' => $_SESSION['ID_CPF_EMPRESA'],
        'adicionais' => $adicionais,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Cadastro Sub-Adicionais');
    echo $template->render('form-sub-adicionais.twig', $params);
});

$app->get('/editar/{id}', function (Request $req, Response $res, $args = []) {
    redirect_login();
    global $template;
    $adicionais = AdicionaisDao::getAdicionais();
    $sub_adicional = SubAdicionaisDao::getSubAdicional($args['id']);
    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'ID_CPF_EMPRESA' => $_SESSION['ID_CPF_EMPRESA'],
        'sub_adicional' => $sub_adicional,
        'adicionais' => $adicionais,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Editar Sub-Adicionais do Produto');
    echo $template->render('form-sub-adicionais.twig', $params);
});

$app->get('/listar', function () {
    redirect_login();
    global $template;
    $sub_adicionais = SubAdicionaisDao::getSubAdicionais();
    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'subadicionais' => $sub_adicionais,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Lista SubAdicionais');
    echo $template->render('lista-sub-adicionais.twig', $params);
});

$container = $app->getContainer();
$container['upload_directory'] = __DIR__ . '/uploads';

$app->post('/salvar', function(Request $request, Response $response) {
    redirect_login();
    $data = $request->getParsedBody();
    $directory = $this->get('upload_directory');
    $uploadedFiles = $request->getUploadedFiles();
    $uploadedFile = $uploadedFiles['sub_img'];

    if ($uploadedFile->getError() != UPLOAD_ERR_OK) {
        echo 'erro no envio da imagem, nao gravou nada';
        exit();
    }

    $filename = moveUploadedFile($data['ID_CPF_EMPRESA'], $directory, $uploadedFile);

    $std = new stdClass();
    $std->ID_CPF_EMPRESA = filter_var($data['ID_CPF_EMPRESA'], FILTER_SANITIZE_STRING);
    $std->sub_descricao = filter_var($data['sub_descricao'], FILTER_SANITIZE_STRING);
    $std->sub_img = $filename;
    $std->sub_preco = filter_var($data['sub_preco'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $std->adc_codigo = filter_var($data['adc_codigo'], FILTER_SANITIZE_NUMBER_INT);
    SubAdicionaisDao::salvar($std);
    return $response->withStatus(302)->withHeader('Location', BASE_URL . '/sub_adicionais/listar');
});

$app->post('/editar', function(Request $request, Response $response) {
    redirect_login();

    $data = $request->getParsedBody();
    $directory = $this->get('upload_directory');
    $uploadedFiles = $request->getUploadedFiles();
    $uploadedFile = $uploadedFiles['sub_img'];

    if ($uploadedFile->getError() != UPLOAD_ERR_OK) {
        return $response->withStatus(302)->withHeader('Location', BASE_URL . '/sub_adicionais/editar/' . $data["sub_codigo"]);
        exit();
    }
    $filename = moveUploadedFile($data['ID_CPF_EMPRESA'], $directory, $uploadedFile);
    unlink($directory . '/' . $data['imagedel']);

    $std = new stdClass();
    $std->ID_CPF_EMPRESA = filter_var($data['ID_CPF_EMPRESA'], FILTER_SANITIZE_STRING);
    $std->sub_descricao = filter_var($data['sub_descricao'], FILTER_SANITIZE_STRING);
    $std->sub_img = $filename;
    $std->sub_preco = filter_var($data['sub_preco'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $std->adc_codigo = filter_var($data['adc_codigo'], FILTER_SANITIZE_NUMBER_INT);
    $std->sub_codigo = filter_var($data['sub_codigo'], FILTER_SANITIZE_NUMBER_INT);
    SubAdicionaisDao::editar($std);
    return $response->withStatus(302)->withHeader('Location', BASE_URL . '/sub_adicionais/listar');
});

$app->get('/excluir/{id}', function (Request $req, Response $res, $args = []) {
    redirect_login();
    global $template, $log;
    $adc_codigo = (int) $args['id'];
    SubAdicionaisDao::delete($adc_codigo);
    return $res->withStatus(302)->withHeader('Location', BASE_URL . '/sub_adicionais/listar');
});

function moveUploadedFile($ID_CPF_EMPRESA, $directory, UploadedFile $uploadedFile) {

    $filename = $uploadedFile->getClientFilename();
    $search = array(' ', '-', '_', '(', ')');
    $filesp = str_replace($search, '', Util::remover_letra_acentuada(strtolower($filename)));
    $rand = rand(0, 100000);
    $file = $ID_CPF_EMPRESA . '_' . $rand . '_subadicionais_' . $filesp;

    if (strlen($file) > 100) {
        echo 'nome da imagem muito grande';
        exit();
    }

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $file);
    return $file;
}

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
