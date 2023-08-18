<?php

namespace App\Controller\Pages;

use App\Controller\Pages\Page;
use App\Http\Request;
use App\Model\Entity\Cliente;
use App\Utils\View;
use http\Client\Curl\User;
use App\Model\Entity\Cliente as M_Cliente;
use \App\Session\Pages\Login as SessionPageLogin;


class Registro extends Page
{
    public static function getRegistro($mensagem = null){

        $status = !is_null($mensagem) ? View::render('pages/login/status',[
            'mensagem' => $mensagem
        ]) : '';

        $content = View::render('pages/register',[
            'status' => $status
        ]);
        return parent::getPageLogin('Controle',$content);
    }

    /**
     * @param Request $request
     * @return string
     */
    public static function setRegistro($request){

        $postVars = $request->getPostVars();
        $obCliente = M_Cliente::getUserByEmail($postVars['email']);

        if(!$obCliente instanceof Cliente){

        $cliente = new M_Cliente();

        $cliente->cliente = $postVars['cliente'];
        $cliente->fantasia = $postVars['fantasia'];
        $cliente->telefone = $postVars['telefone'];
        $cliente->cnpj = $postVars['cnpj'];
        $cliente->ie = $postVars['ie'];
        // $cliente->rg = $postVars['rg'];
        $cliente->cpf = $postVars['cpf'];
        $cliente->cep = $postVars['cep'];
        $cliente->endereco = $postVars['endereco'];
        $cliente->numero = $postVars['numero'];
        $cliente->bairro = $postVars['bairro'];
        $cliente->cidade = $postVars['cidade'];
        $cliente->uf = $postVars['uf'];
        $cliente->complemento = $postVars['complemento'];
        $cliente->email = $postVars['email'];
        $cliente->senha = $postVars['senha'];

        $cliente->cadastrar();

            header("Location: http://localhost/comgelo/login");
            die();

        }

        return self::getRegistro('Email Já Cadastrado');

    }

}