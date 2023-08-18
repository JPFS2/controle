<?php

namespace App\Utils;

use App\Controller\Pages\Home;

class View
{
    private static $vars;
    public static function init($vars = []){
        self::$vars = $vars;
    }

    /**
     * Metodo Responsavel por retorna o conteudo da vieW
     * @param $view
     * @return string
     */
    public static function getContentView($view){
         $file = __DIR__ .'/../../resources/view/'.$view.'.html';
         return file_exists($file) ? file_get_contents($file) : $file;
    }

    /** Metodo Responsavel por retornar o conteudo renderizado de uma view
     * @param string $view
     * @param array texto e numeros
     * @return string
     */
    public static function render($view,$vars = [] ){

        // Recebendo conteudo da view
        $contentView =  self::getContentView($view);

        $vars = array_merge(self::$vars,$vars);

        // recebendo chaves de variavel
        $keys = array_keys($vars);
        $keys = array_map(function ($item){
            return'{{'.$item.'}}';
        },$keys);


        return  str_replace($keys,array_values($vars),$contentView);
    }

}