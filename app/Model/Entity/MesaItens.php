<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class MesaItens
{

public $codmesaitem;
public $codmesa;
public $codprod;
public $qt;
public $precounitario;
public $precototal;
public $dtentrega;
public $tipo;

    public function cadastrar(){

        $this->dtpedido = Date('Y-m-d H:i');

        $obDatabase = new Database('mesaitens');
        $id = $obDatabase->insert([
            'codmesa' => $this->codmesa,
            'codprod' => $this->codprod,
            'qt' => $this->qt,
            'precounitario' => $this->precounitario,
            'precototal' => round(($this->precounitario * $this->qt),2)

        ]);

        return $id;
    }

    public static function buscar($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('mesaitens'))->select($where,$order,$limit,$filds);
    }


    public static function buscarItens($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('mesaitens'))->selectJoin($where,$order,$limit,$filds,'cadprod', 'mesaitens.codprod = cadprod.codprod');
    }

    public static function buscarItensCodcli($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('pedidoitens'))->selectJoin($where,$order,$limit,$filds,'pedido', 'pedidoitens.numped = pedido.numped');
    }
}