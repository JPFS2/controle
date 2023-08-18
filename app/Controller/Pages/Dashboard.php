<?php 

namespace App\Controller\Pages;
use App\Controller\Pages\Page;
use App\Http\Request;
use App\Model\Entity\mesa as M_Mesa;
use App\Model\Entity\MesaItens as M_MesaItem;
use App\Model\Entity\Pedido as M_Pedido;
use App\Utils\View;

class Dashboard extends Page {

    /**
    * Metodo resposave por retornar a tela de deshboard
    *  @retunr string 
    */    
    public static function getDashboard(){

        $content = View::render('pages/dashboard',[
            'mesa' => self::getMesa(),
        ]);

        return parent::getPage('Controle',$content);
    }

    /**
     * Responsavel por trazer as mesas para tela de deshboard
     * @return string
     */

    public static function getMesa(){
        $tr = '';

        for ($i = 1; $i < 26; $i++){

            $qt = M_MesaItem::buscar('codmesa = '.$i,null,null,'count(*) as qt')->fetchObject()->qt;

            $color = $qt > 0 ? 'bg-warning' : 'bg-danger';

            $tr .= View::render('pages/dashboard/mesa',[
                'mesa' => $i,
                'collor' => $color
            ]);
        }

        return $tr;
    }

}