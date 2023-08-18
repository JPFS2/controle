<?php

namespace App\Controller\Pages;
use App\Utils\View;
use App\Model\Entity\Organization;

class Sobre extends Page {
    /*
    * Metodo resposave por retornar o conteudo da view do home
    *  @retunr string
    */
    public static function getSobre(){

        $obOrganization = new Organization();

        $content = View::render('pages/sobre',[
            'title' =>$obOrganization->nome,
            'nome'  =>'Marlon',
            'email' =>'marlonfsantos@gmail.com',
            'sobre' => 'Powerful, extensible, and feature-packed frontend toolkit. Build and customize with Sass, utilize prebuilt grid system and components, and bring projects to life with powerful JavaScript plugins. '
        ]);

        return parent::getPage('Comgelo > Sobre',$content);
    }

}