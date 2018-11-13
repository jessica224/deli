<?php

ob_start();
error_reporting(E_ALL ^ E_NOTICE);

require_once '../vendor/autoload.php';
require_once '../config.php';

use App\Util\Util;
use App\Model\CategoriaDao;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\UploadedFile;

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
        'TITLE' => 'Cadastro de Categorias');
    echo $template->render('form-categoria.twig', $params);
});

$app->get('/editar/{id}', function (Request $req, Response $res, $args = []) {
    redirect_login();
    global $template;
    $categoria = CategoriaDao::getCategoria($args['id']);
    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'ID_CPF_EMPRESA' => $_SESSION['ID_CPF_EMPRESA'],
        'categoria' => $categoria,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Editar Categoria');
    echo $template->render('form-categoria.twig', $params);
});

$app->get('/listar', function () {
    redirect_login();
    global $template;
    $categorias = CategoriaDao::getCategorias();
    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'categorias' => $categorias,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Lista de Categorias');
    echo $template->render('lista-categorias.twig', $params);
});

$container = $app->getContainer();
$container['upload_directory'] = __DIR__ . '/uploads';

$app->post('/salvar', function(Request $request, Response $response) {
    redirect_login();
    $data = $request->getParsedBody();

    $directory = $this->get('upload_directory');
    $uploadedFiles = $request->getUploadedFiles();
    $uploadedFile = $uploadedFiles['cat_imagem'];

    if ($uploadedFile->getError() != UPLOAD_ERR_OK) {
        echo 'erro no envio da imagem, nao gravou nada';
        exit();
    }
    $filename = moveUploadedFile($data['ID_CPF_EMPRESA'], $directory, $uploadedFile);

    $std = new stdClass();
    $std->ID_CPF_EMPRESA = filter_var($data['ID_CPF_EMPRESA'], FILTER_SANITIZE_STRING);
    $std->cat_descricao = filter_var($data['cat_descricao'], FILTER_SANITIZE_STRING);
    $std->cat_imagem = $filename;
    CategoriaDao::salvar($std);
    return $response->withStatus(302)->withHeader('Location', BASE_URL . '/categorias/listar');
});

$app->post('/editar', function(Request $request, Response $response) {
    redirect_login();
    $data = $request->getParsedBody();
    $directory = $this->get('upload_directory');
    $uploadedFiles = $request->getUploadedFiles();
    $uploadedFile = $uploadedFiles['cat_imagem'];

    if ($uploadedFile->getError() != UPLOAD_ERR_OK) {
        echo 'erro no envio da imagem, nao gravou nada';
        exit();
    }
    $filename = moveUploadedFile($data['ID_CPF_EMPRESA'], $directory, $uploadedFile);
    unlink($directory . '/' . $data['imagedel']);


    $std = new stdClass();
    $std->cat_codigo = filter_var($data['cat_codigo'], FILTER_SANITIZE_NUMBER_INT);
    $std->ID_CPF_EMPRESA = filter_var($data['ID_CPF_EMPRESA'], FILTER_SANITIZE_STRING);
    $std->cat_descricao = filter_var($data['cat_descricao'], FILTER_SANITIZE_STRING);
    $std->cat_imagem = $filename;
    CategoriaDao::editar($std);
    return $response->withStatus(302)->withHeader('Location', BASE_URL . '/categorias/listar');
});

$app->get('/excluir/{id}', function (Request $req, Response $res, $args = []) {
    redirect_login();
    global $template;
    $cat_codigo = (int) $args['id'];
    CategoriaDao::delete($cat_codigo);
    return $res->withStatus(302)->withHeader('Location', BASE_URL . '/categorias/listar');
});

function moveUploadedFile($ID_CPF_EMPRESA, $directory, UploadedFile $uploadedFile) {

    $filename = $uploadedFile->getClientFilename();
    $search = array(' ', '-', '_', '(', ')');
    $filesp = str_replace($search, '', strtolower($filename));
    $rand = rand(0, 100000);
    $file = $ID_CPF_EMPRESA . '_' . $rand . '_categoria_' . $filesp;

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
