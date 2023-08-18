<?php 

namespace App\Controller\Admin;
use App\Controller\Admin\Page;
use App\Model\Entity\Pedido as M_Pedido;
use App\Model\Entity\PedidoPagamento as M_PedidoPag;
use App\Model\Entity\Empresa as M_Empresa;
use App\Utils\View;

class Pedido extends Page {

    /*
    * Metodo resposave por retornar o conteudo da view do home
    *  @retunr string 
    */
    public static function getPedidos(){
        $tr = '';
        $pedidos = M_Pedido::buscar(null,null,null,'pedido.* , cadcliente.cliente');

        while ($pedido = $pedidos->fetchObject()){

            $status = isset($pedido->cxencerrado) ? '<span class="badge bg-success">Acertado</span>': '<span class="badge bg-warning">Aguardando Acerto</span>';

            $date = date_create($pedido->dtpedido);

            $tr .= View::render('admin/controlepedidos/pedidos', [
                'numped' => $pedido->numped,
                'mesa' =>  $pedido->codmesa,
                'cliente' =>  $pedido->cliente,
                'dtpedido' => date_format($date,'d/m/Y - H:m'),
                'vlliq' => $pedido->vltotal,
                'status' => $status
            ]);
        }

        $content = View::render('admin/controlepedidos', [
            'tr' => $tr,
            'usuario' => $_SESSION['admin']['usuario']['nome']
        ]);

        return parent::getPageLogin('Controle',$content);
    }
    public static function getPrint($id){


        $empresa = M_Empresa::buscar()->fetchObject();

        $pedido = M_Pedido::buscar('numped = '.$id,null,null,'pedido.* ,
         cadcliente.cliente, cadcliente.telefone, cadcliente.email, cadcliente.endereco, cadcliente.numero,
         cadcliente.bairro, cadcliente.cidade, cadcliente.uf')->fetchObject();

        $date = date_create($pedido->dtpedido);

        $content = View::render('admin/invoice-print', [

            'usuario' => $_SESSION['admin']['usuario']['nome'],

            'empresa' => $empresa->razaosocial,
            'fantasia' => $empresa->fantasia,
            'empendereco' => $empresa->endereco,
            'empnumero' => $empresa->numero,
            'empbairro' => $empresa->bairro,
            'empcidade' => $empresa->cidade,
            'empuf' => $empresa->uf,
            'emptelefone' => $empresa->telefone,
            'empemail' => $empresa->email,

            'numped' => $id,
            'data' => date_format($date,'d/m/Y - H:m    '),


            'vltotal' => $pedido->vltotal,
            'mesa' => $pedido->codmesa,

            'tr' => self::getPedidoItens($id),
            'moedas' => self::getPagItens($id),
            'garcon' => $pedido->cliente,
        ]);
        return parent::getPageLogin('Controle',$content);
    }


    public static function getPedidoItens($id){

        $tr = '';
        $itens = M_Pedido::buscarItens('numped = '.$id,null,null,'pedidoitens.*, cadprod.descricao');

        while ($item = $itens->fetchObject()){


            $tr .= View::render('admin/controlepedido/produtos', [
                'codprod' => $item->codprod,
                'descricao' =>  $item->descricao,
                'qt' => $item->qt,
                'punit' => $item->punit,
                'vlliq' => $item->vlliq
            ]);
        }
        return $tr;

    }

    public static function getPagItens($id){

        $tr = '';
        $itens = M_PedidoPag::buscarItens('numped = '.$id,null,null,'*');

        while ($item = $itens->fetchObject()){

            $tr .= View::render('admin/controlepedido/moedas', [
                'moeda' => $item->moeda,
                'vltotal' =>  str_replace('.',',',round($item->valor,3)),

            ]);
        }
        return $tr;

    }
    public static function  getPedido($id){

        $empresa = M_Empresa::buscar()->fetchObject();

        $pedido = M_Pedido::buscar('numped = '.$id,null,null,'pedido.* ,
         cadcliente.cliente, cadcliente.telefone, cadcliente.email, cadcliente.endereco, cadcliente.numero,
         cadcliente.bairro, cadcliente.cidade, cadcliente.uf')->fetchObject();
        $date = date_create($pedido->dtpedido);



        $content = View::render('admin/controlepedidoss', [

            'usuario' => $_SESSION['admin']['usuario']['nome'],

            'empresa' => $empresa->razaosocial,
            'fantasia' => $empresa->fantasia,
            'empendereco' => $empresa->endereco,
            'empnumero' => $empresa->numero,
            'empbairro' => $empresa->bairro,
            'empcidade' => $empresa->cidade,
            'empuf' => $empresa->uf,
            'emptelefone' => $empresa->telefone,
            'empemail' => $empresa->email,

            'numped' => $id,
            'data' => date_format($date,'d/m/Y - H:m    '),


            'vltotal' => str_replace('.',',',round($pedido->vltotal,2)),
            'mesa' => $pedido->codmesa,

            'tr' => self::getPedidoItens($id),
            'moedas' => self::getPagItens($id),
            'garcon' => $pedido->cliente,
        ]);

        return parent::getPageLogin('Controle',$content);
    }

    }


