<?php 

namespace App\Controller\Admin;
use App\Controller\Admin\Page;
use App\Model\Entity\Pedido as M_Pedido;
use App\Model\Entity\Empresa as M_Empresa;
use App\Utils\View;

class ControlePedido extends Page {

    /*
    * Metodo resposave por retornar o conteudo da view do home
    *  @retunr string 
    */
    public static function getPedidos(){

        $codigoCliente = $_SESSION['admin']['usuario']['id'];

        $tr = '';
        $pedidos = M_Pedido::buscar('pedido.codcli = '.$codigoCliente,null,null,'pedido.* , cadcliente.cliente');

        while($pedido = $pedidos->fetchObject()){

            $status = isset($pedido->dtliberado) ? '<span class="badge bg-success">Liberado</span>': '<span class="badge bg-warning">Aguardando Liberação</span>';


            $date = date_create($pedido->dtpedido);

            $tr .= View::render('admin/controlepedido/pedidos', [
                'numped' => $pedido->numped,
                'cliente' =>  $pedido->cliente,
                'dtpedido' => date_format($date,'d/m/Y'),
                'vlliq' => $pedido->vlliquido,
                'status' => $status
            ]);

        }

        $content = View::render('admin/controlepedidos', [
            'tr' => $tr
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
    public static function  getPedido($id){

        $empresa = M_Empresa::buscar()->fetchObject();

        $pedido = M_Pedido::buscar('numped = '.$id,null,null,'pedido.* ,
         cadcliente.cliente, cadcliente.telefone, cadcliente.email, cadcliente.endereco, cadcliente.numero,
         cadcliente.bairro, cadcliente.cidade, cadcliente.uf')->fetchObject();
        $date = date_create($pedido->dtpedido);
        $dateLiberacao = date_create($pedido->dtliberado);
        $dateEnvio = date_create($pedido->DtEntrega);

        $content = View::render('admin/controlepedidoss', [
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
            'data' => date_format($date,'d/m/Y'),
            'dtaprovacao' => date_format($dateLiberacao,'d/m/Y'),
            'dtenvio' => date_format($dateEnvio,'d/m/Y'),
            'vltotal' => $pedido->vltotal,
            'vlfrete' => isset($pedido->vlfrete) ? $pedido->vlfrete : '0.00',
            'vlliquido' => $pedido->vlliquido,
            'obs' =>  $pedido->obs,

            'tr' => self::getPedidoItens($id),

            'cliente' => $pedido->cliente,
            'endereco' => $pedido->endereco,
            'endereco' => $pedido->endereco,
            'numero' => $pedido->numero,
            'bairro' => $pedido->bairro,
            'cidade' => $pedido->cidade,
            'uf' => $pedido->uf,
            'telefone' => $pedido->telefone,
            'email' => $pedido->email
        ]);

        return parent::getPageLogin('Controle',$content);
    }

    }


