<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class PedidoItens
{
    public $coditem;
    public $numped;
    public $codprod;
    public $punit;
    public $qt;
    public $vlliq;
    public $dtpedido;
    public $obs;

    public function cadastrar(){

        $this->dtpedido = Date('Y-m-d H:i');

        $obDatabase = new Database('pedidoitens');
        $id = $obDatabase->insert([
            'numped' => $this->numped,
            'codprod' => $this->codprod,
            'qt' => $this->qt,
            'punit' => $this->punit,
            'vlliq' => $this->vlliq

        ]);

        return $id;
    }
    public static function buscar($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('pedido'))->selectJoin($where,$order,$limit,$filds,'cadcliente', 'pedido.codcli = cadcliente.codcli');
    }

    public static function buscarQt($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('pedido'))->select($where,$order,$limit,$filds);
    }

    public static function buscarItens($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('pedidoitens'))->selectJoin($where,$order,$limit,$filds,'cadprod', 'pedidoitens.codprod = cadprod.codprod');
    }

    public static function buscarItensCodcli($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('pedidoitens'))->selectJoin($where,$order,$limit,$filds,'pedido', 'pedidoitens.numped = pedido.numped');
    }
}