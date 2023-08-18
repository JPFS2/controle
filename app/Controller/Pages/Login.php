<?php

namespace App\Controller\Pages;

use App\Controller\Pages\Page;
use App\Http\Request;
use App\Utils\View;
use http\Client\Curl\User;
use App\Model\Entity\Cliente;
use \App\Session\Pages\Login as SessionPageLogin;

class Login extends Page
{
    public static function getLogin($request, $errorMessage = null){
        $status = !is_null($errorMessage) ? View::render('pages/login/status',[
            'mensagem' => $errorMessage
        ]) : '';

        $content = View::render('pages/login',[
            'status' => $status
        ]);

        return parent::getPageLogin('Comgelo > login',$content);
    }

    public static function setLogin($request){

        $postVars = $request->getPostVars();
        $email = $postVars['email'];
        $senha = $postVars['senha'];

        $obCliente = Cliente::getUserByEmail($email);

        if(!$obCliente instanceof Cliente){
            return self::getLogin($request, 'Email ou senha invalidos');
        }


        if(!($senha == $obCliente->senha)){
            return self::getLogin($request, 'Email ou senha invalidos');
        }

        // Inicia a tela de login
        SessionPageLogin::login($obCliente);

        $request->getRouter()->redirect('/');

    }

    /**
     * @param Request $request
     * @return void
     */
    public static function setLogout($request){
        SessionPageLogin::logout();

        $request->getRouter()->redirect('/login');
    }

}