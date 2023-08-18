<?php 

namespace App\Controller\Pages;
use App\Utils\View;
use App\Model\Entity\Organization;

class Home extends Page {
    /*
    * Metodo resposave por retornar o conteudo da view do home
    *  @retunr string 
    */    
    public static function getHome(){

        $obOrganization = new Organization();

        $content = View::render('pages/home',[
            'title' =>$obOrganization->nome,
        ]);

        return parent::getPage('Controle',$content);
    }

}