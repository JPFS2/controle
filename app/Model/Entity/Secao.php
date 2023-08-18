<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class Secao
{
 public $codsecao;
 public $secao;

    public function atualizar(){

        $obDatabase = new Database('cadcliente');
        $success = $obDatabase->update('codcli ='.$this->codcli,[
        ]);

    }

    public static function buscar($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('cadsecao'))->select($where,$order,$limit,$filds);
    }




}