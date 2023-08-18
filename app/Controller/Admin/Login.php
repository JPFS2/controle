<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Utils\View;
use http\Client\Curl\User;
use App\Model\Entity\Usuario;
use \App\Session\Admin\Login as SessionAdminLogin;

class Login extends Page
{
    public static function getLogin($request, $errorMessage = null){
        $status = !is_null($errorMessage) ? View::render('admin/login/status',[
            'mensagem' => $errorMessage
        ]) : '';

        $content = View::render('admin/login',[
            'status' => $status
        ]);

        return parent::getPageLogin('Comgelo > login',$content);
    }

    public static function setLogin($request){

        $postVars = $request->getPostVars();
        $email = $postVars['email'];
        $senha = $postVars['senha'];

        $obUsuario = Usuario::getUserByEmail($email);

        if(!$obUsuario instanceof Usuario){
            return self::getLogin($request, 'Email ou senha invalidos');
        }


        if(!($senha == $obUsuario->senha)){
            return self::getLogin($request, 'Email ou senha invalidos');
        }

        // Inicia a tela de login
        SessionAdminLogin::login($obUsuario);

        $request->getRouter()->redirect('/admin');



    }

    /**
     * @param Request $request
     * @return void
     */
    public static function setLogout($request){
        SessionAdminLogin::logout();

        $request->getRouter()->redirect('/admin/login');
    }

}