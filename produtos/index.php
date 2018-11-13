<?php

ob_start();
error_reporting(E_ALL ^ E_NOTICE);

require '../vendor/autoload.php';
require_once '../config.php';

use App\Util\Util;
use App\Model\ProdutoDao;
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
    $categorias = CategoriaDao::getCategorias();
    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'ID_CPF_EMPRESA' => $_SESSION['ID_CPF_EMPRESA'],
        'categorias' => $categorias,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Cadastro de Produtos');
    echo $template->render('form-produtos.twig', $params);
});

$app->get('/editar/{id}', function (Request $req, Response $res, $args = []) {
    redirect_login();
    global $template;
    $categorias = CategoriaDao::getCategorias();
    $produto = ProdutoDao::getProduto($args['id']);
    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'ID_CPF_EMPRESA' => $_SESSION['ID_CPF_EMPRESA'],
        'produto' => $produto,
        'categorias' => $categorias,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Editar Produto');
    echo $template->render('form-produtos.twig', $params);
});

$app->get('/listar', function () {
    redirect_login();
    global $template;
    $produtos = ProdutoDao::getProdutos();
    $EMPRESA_OPERACAO = file_get_contents('../empresa.txt');
    $params = array(
        'USU_NIVEL' => $_SESSION['usu_nivel'],
        'EMPRESA_OPERACAO' => $EMPRESA_OPERACAO,
        'produtos' => $produtos,
        'BASE_URL' => BASE_URL,
        'TITLE' => 'Lista de Produtos');
    echo $template->render('lista-produtos.twig', $params);
});

$container = $app->getContainer();
$container['upload_directory'] = __DIR__ . '/uploads';

$app->post('/salvar', function(Request $request, Response $response) {
    redirect_login();
    $data = $request->getParsedBody();
    $directory = $this->get('upload_directory');
    $uploadedFiles = $request->getUploadedFiles();
    $uploadedFile = $uploadedFiles['prd_img'];

    if ($uploadedFile->getError() != UPLOAD_ERR_OK) {
        echo 'erro no envio da imagem, nao gravou nada';
        exit();
    }

    $filename = moveUploadedFile($data['ID_CPF_EMPRESA'], $directory, $uploadedFile);

    $std = new stdClass();
    $std->ID_CPF_EMPRESA = filter_var($data['ID_CPF_EMPRESA'], FILTER_SANITIZE_STRING);
    $std->cat_codigo = ($data['cat_codigo'] == 0) ? 1 : $data['cat_codigo'];
    $std->prd_descricao = filter_var($data['prd_descricao'], FILTER_SANITIZE_STRING);
    $std->prd_preco = filter_var($data['prd_preco'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $std->prd_img = $filename;
    $std->prd_prom = ($data['prd_prom'] == 'S') ? 'S' : 'N';
    $std->prd_det_1 = filter_var($data['prd_det_1'], FILTER_SANITIZE_STRING);
    $std->prd_det_2 = filter_var($data['prd_det_2'], FILTER_SANITIZE_STRING);
    $std->prd_ativo = ($data['prd_ativo'] == 'S') ? 'S' : 'N';
    ProdutoDao::salvar($std);
    return $response->withStatus(302)->withHeader('Location', BASE_URL . '/produtos/listar');
});

$app->post('/editar', function(Request $request, Response $response) {
    redirect_login();

    $data = $request->getParsedBody();
    $directory = $this->get('upload_directory');
    $uploadedFiles = $request->getUploadedFiles();
    $uploadedFile = $uploadedFiles['prd_img'];

    if ($uploadedFile->getError() != UPLOAD_ERR_OK) {
        return $response->withStatus(302)->withHeader('Location', BASE_URL . '/produtos/editar/' . $data["prd_codigo"]);
        exit();
    }
    $filename = moveUploadedFile($data['ID_CPF_EMPRESA'], $directory, $uploadedFile);
    unlink($directory . '/' . $data['imagedel']);

    $std = new stdClass();
    $std->ID_CPF_EMPRESA = filter_var($data['ID_CPF_EMPRESA'], FILTER_SANITIZE_STRING);
    $std->cat_codigo = ($data['cat_codigo'] == 0) ? 1 : $data['cat_codigo'];
    $std->prd_descricao = filter_var($data['prd_descricao'], FILTER_SANITIZE_STRING);
    $std->prd_preco = filter_var($data['prd_preco'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $std->prd_img = $filename;
    $std->prd_prom = ($data['prd_prom'] == 'S') ? 'S' : 'N';
    $std->prd_det_1 = filter_var($data['prd_det_1'], FILTER_SANITIZE_STRING);
    $std->prd_det_2 = filter_var($data['prd_det_2'], FILTER_SANITIZE_STRING);
    $std->prd_ativo = ($data['prd_ativo'] == 'S') ? 'S' : 'N';
    $std->prd_codigo = filter_var($data['prd_codigo'], FILTER_SANITIZE_NUMBER_INT);
    ProdutoDao::editar($std);
    return $response->withStatus(302)->withHeader('Location', BASE_URL . '/produtos/listar');
});

$app->get('/excluir/{id}', function (Request $req, Response $res, $args = []) {
    redirect_login();
    global $template;
    $prd_codigo = (int) $args['id'];
    ProdutoDao::delete($prd_codigo);
    return $res->withStatus(302)->withHeader('Location', BASE_URL . '/produtos/listar');
});

function moveUploadedFile($ID_CPF_EMPRESA, $directory, UploadedFile $uploadedFile) {

    $filename = $uploadedFile->getClientFilename();
    $search = array(' ', '-', '_', '(', ')');
    $filesp = str_replace($search, '', strtolower($filename));
    $rand = rand(0, 100000);
    $file = $ID_CPF_EMPRESA . '_' . $rand . '_produto_' . $filesp;

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
