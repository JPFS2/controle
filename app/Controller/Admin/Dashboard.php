<?php 

namespace App\Controller\Admin;
use App\Controller\Admin\Page;
use App\Model\Entity\mesa as M_Mesa;
use App\Model\Entity\Organization;
use App\Model\Entity\Pedido as M_Pedido;
use App\Utils\View;

class Dashboard extends Page {
    /*
    * Metodo resposave por retornar o conteudo da view do home
    *  @retunr string 
    */
    public static function getDashboard(){

        $content = View::render('admin/mesas',[
            'mesa' => self::getMesa(),
        ]);

        return parent::getPage('Controle',$content);
    }

    public static function getMesa(){
        $tr = '';

        for ($i = 1; $i < 26; $i++){
            $qt = M_Mesa::buscarItens('codmesa = '.$i,null,null,'count(*) as qt')->fetchObject()->qt;

            $color = $qt > 0 ? 'bg-warning' : 'bg-danger';

            $tr .= View::render('admin/dashboard/mesa',[
                'mesa' => $i,
                'collor' => $color
            ]);
        }

        return $tr;
    }

    public static function getQtPedidos(){
        $codigoCliente = isset($_SESSION['pages']['usuario']['id']) ? $_SESSION['pages']['usuario']['id'] : '';
        $qt = M_Pedido::buscarQt('pedido.codcli ='. $codigoCliente,null,null,'COUNT(*) as qtd')->fetchObject()->qtd;
        return $qt;
    }

    public static function getQtComodatos(){
        $codigoCliente = isset($_SESSION['pages']['usuario']['id']) ? $_SESSION['pages']['usuario']['id'] : '';
        $qt = M_Pedido::buscarItensCodcli("pedido.codcli = $codigoCliente and pedidoitens.tipo = 'C' and dtDevolucao is null",null,null,'COUNT(*) as qtd')->fetchObject()->qtd;

        if($qt == ''){
            $qt = 0;
        }
        return $qt;
    }
    public static function getVlCarrinho()
    {
        $codigoCliente = isset($_SESSION['pages']['usuario']['id']) ? $_SESSION['pages']['usuario']['id'] : '';
        $qt = M_Carrinho::buscar('codcli = ' . $codigoCliente.' and tipo = "P"', null, null, 'SUM(vlliq) as valor')->fetchObject()->valor;
        if($qt == ''){
            $qt = '0,00';
        }
        return $qt;

    }


}