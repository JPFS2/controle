<?php

namespace App\Session\Pages;

use App\Model\Entity\Cliente;

class Login
{

    public static function init(){
        if(session_status() != PHP_SESSION_ACTIVE){
            session_start();
        }
    }

    /**
     * Metodo responsavel por realizar o login
     * @param Cliente $obCliente
     * @return boolean
     */
    public static function login($obCliente){

        self::init();



        $_SESSION['pages']['usuario'] = [
            'id' => $obCliente->codcli,
            'nome' => $obCliente->cliente,
            'email' => $obCliente->email,
        ];


        return true;
    }

    public static function isLogged(){
        self::init();

        return isset($_SESSION['pages']['usuario']['id']);
    }

    /**
     * Metodo Responsavel por realziar o logout
     * @return boolean
     */
    public static function logout(){
        self::init();

        unset($_SESSION['pages']['usuario']);
        return true;
    }

}