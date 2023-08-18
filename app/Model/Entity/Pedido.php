<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class Pedido
{
    public $numped;
    public $codcli;
    public $codmesa;
    public $dtpedido;
    public $vltotal;
    public $cxencerrado;
    public $fechador_caixa;
    public $imprimir;

    public function cadastrar(){

        $this->dtpedido = Date('Y-m-d H:i');

        $obDatabase = new Database('pedido');
        $id = $obDatabase->insert([
            'codmesa' => $this->codmesa,
            'codcli' => $this->codcli,
            'dtpedido' => $this->dtpedido,
            'vltotal' => $this->vltotal,

        ]);

        return $id;
    }
    public static function buscar($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('pedido'))->selectJoin($where,$order,$limit,$filds,'cadcliente', 'pedido.codcli = cadcliente.codcli');
    }

    public static function buscarQt($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('pedido'))->select($where,$order,$limit,$filds);
    }

    public static function atualizar($func){

        $dtpedido = Date('Y-m-d H:i');

        $obDatabase = new Database('pedido');
        $success = $obDatabase->update('codcli ='.$func.' and cxencerrado is null',[
            'cxencerrado' =>  $dtpedido
        ]);

    }

    public static function buscarItens($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('pedidoitens'))->selectJoin($where,$order,$limit,$filds,'cadprod', 'pedidoitens.codprod = cadprod.codprod');
    }

    public static function buscarItensCodcli($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('pedidoitens'))->selectJoin($where,$order,$limit,$filds,'pedido', 'pedidoitens.numped = pedido.numped');
    }
}