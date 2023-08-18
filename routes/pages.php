<?php

use App\Controller\Pages;
use App\Http\Response;

$obRouter->get('/',[
    'middlewares' => [
        'required-pages-login'
    ],
    function(){
        return new Response(200, Pages\Dashboard::getDashboard());
    }
]);

$obRouter->get('/mesa/{id}',[
    'middlewares' => [
        'required-pages-login'
    ],
    function($request,$id){
        return new Response(200, Pages\Mesa::getMesa($request,$id));
    }
]);

$obRouter->post('/buscamesa',[
    'middlewares' => [
        'required-pages-login'
    ],
    function($request,$id){
        return new Response(200, Pages\Mesa::buscaMesa($request));
    }
]);


// Rota realiza o Login
$obRouter->post('/add', [
    'middlewares' => [
        'required-pages-login'
    ],
    function ($request) {
        return new Response(200, Pages\Mesa::addProduto($request));
    }
]);

$obRouter->get('/removemoeda/{coditem}',[
    'middlewares' => [
        'required-pages-login'
    ],
    function($coditem){
        return new Response(200,Pages\Mesa::removeMoeda($coditem));
    }
]);

// Rota realiza o Login
$obRouter->post('/addPmesa', [
    'middlewares' => [
        'required-pages-login'
    ],
    function ($request) {
        return new Response(200, Pages\Pagamento::addPMesa($request));
    }
]);

$obRouter->get('/produtos',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Pages\Produto::getProdutos($request));
    }
]);

// Rota que mostra a tela de login
$obRouter->get('/login', [
    'middlewares' => [
        'required-pages-logout'
    ],
    function ($request) {
        return new Response(200, Pages\Login::getLogin($request));
    }
]);

// Rota realiza o Login
$obRouter->post('/login', [
    function ($request) {
        return new Response(200, Pages\Login::setLogin($request));
    }
]);

// Rota que realiza o Logout
$obRouter->get('/logout', [
    function ($request) {

        return new Response(200, Pages\Login::setLogout($request));
    }
]);


// Rota cliente
$obRouter->get('/controlepedidos', [
    'middlewares' => [
        'required-pages-login'
    ],
    function ($request) {
        return new Response(200,Pages\ControlePedido::getPedidos());
    }
]);

$obRouter->get('/controlepedidos/{id}',[
    'middlewares' => [
        'required-pages-login'
    ],
    function($request,$id){
        return new Response(200,Pages\ControlePedido::getPedido($id));
    }
]);

