<?php


use App\Controller\Admin;
use App\Http\Response;

$obRouter->get('/admin', [
    'middlewares' => [
        'required-admin-login'
    ],
    function () {
        return new Response(200, Admin\Dashboard::getDashboard());
    }
]);

$obRouter->get('/admin/mesa/{id}',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$id){
        return new Response(200, Admin\Mesa::getMesa($request,$id));
    }
]);

// Rota realiza o Login
$obRouter->post('/admin/add', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request) {
        return new Response(200, Admin\Mesa::addProduto($request));
    }
]);

$obRouter->get('/admin/removeitem/{coditem}',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($coditem){
        return new Response(200,Admin\Mesa::removeProduto($coditem));
    }
]);

$obRouter->post('/admin/addPmesa', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request) {
        return new Response(200, Admin\Pagamento::addPMesa($request));
    }
]);

// Rota que mostra a tela de login
$obRouter->get('/admin/login', [
    'middlewares' => [
        'required-admin-logout'
    ],
    function ($request) {
        return new Response(200, Admin\Login::getLogin($request));
    }
]);

// Rota realiza o Login
$obRouter->post('/admin/login', [
    'middlewares' => [
        'required-admin-logout'
    ],
    function ($request) {
        return new Response(200, Admin\Login::setLogin($request));
    }
]);

// Rota que realiza o Logout
$obRouter->get('/admin/logout', [
    function ($request) {

        return new Response(200, Admin\Login::setLogout($request));
    }
]);

// Rota que mostra a tela de empresa
$obRouter->get('/admin/empresa',[
    'middlewares' => [
        'required-admin-login'
    ],
    function(){
        return new Response(200,Admin\Empresa::getEmpresa());
    }
]);

// Rota que atuaiza os dados da emrpesa
$obRouter->post('/admin/empresa',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Empresa::atualizaEmpresa($request));
    }
]);


$obRouter->get('/admin/clientes',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Cliente::getClientes($request));
    }
]);

$obRouter->get('/admin/clientes/{idPagina}',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$idPagina){
        return new Response(200, Admin\Cliente::editClientes($request,$idPagina));
    }
]);

$obRouter->post('/admin/clientes',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Cliente::atualizaClientes($request));
    }
]);

$obRouter->get('/admin/cliente',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Cliente::insereCliente($request));
    }
]);

$obRouter->post('/admin/cliente',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Cliente::cadastraCliente($request));
    }
]);


$obRouter->get('/admin/produtos',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Produto::getProdutos($request));
    }
]);

$obRouter->get('/admin/produtos/{id}',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$id){
        return new Response(200, Admin\Produto::editProdutos($request,$id));
    }
]);

$obRouter->post('/admin/produtos',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Produto::atualizaProduto($request));
    }
]);

$obRouter->get('/admin/produto',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Produto::addProduto($request));
    }
]);

$obRouter->post('/admin/produto',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Produto::adicionaProduto($request));
    }
]);
$obRouter->get('/admin/creditos',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Credito::getProdutos($request));
    }
]);

$obRouter->get('/admin/creditos/{id}',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$id){
        return new Response(200, Admin\Credito::editProdutos($request,$id));
    }
]);

$obRouter->post('/admin/creditos',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Credito::atualizaProduto($request));
    }
]);

$obRouter->get('/admin/credito',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Credito::addProduto($request));
    }
]);

$obRouter->post('/admin/credito',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Credito::adicionaProduto($request));
    }
]);


$obRouter->get('/admin/controlepedidos',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200,Admin\ControlePedido::getPedidos());
    }
]);

$obRouter->get('/admin/controlepedidos/{id}',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$id){
        return new Response(200,Admin\ControlePedido::getPedido($id));
    }
]);

$obRouter->get('/admin/conferenciacaixa',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$id){
        return new Response(200,Admin\ConferenciaPagamento::getPagamentoCaixa());
    }
]);

$obRouter->get('/admin/conferenciacaixa/{id}',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$id){
        return new Response(200,Admin\ConferenciaPagamento::getRecebimentoFuncionario($id));
    }
]);


$obRouter->get('/admin/impressaopedido/{id}',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$id){
        return new Response(200,Admin\Pedido::getPrint($id));
    }
]);



$obRouter->get('/admin/acertaconta/{id}',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$id){
        return new Response(200,Admin\ConferenciaPagamento::confirmaRecebientoFuncionario($id));
    }
]);
