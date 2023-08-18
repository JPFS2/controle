<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;
class Credito
{
 public $codprod;
 public $descricao;
 public $codsecao;
 public $und = 'UND';
 public $peso;
 public $pcompra = 0;
 public $punit;
 public $qtestoque;
 public $vlcredito = 0;
 public $codcli;
 public $dtbaixa;

    public function credito(){

        $obDatabase = new Database('credcli');
        $success = $obDatabase->insert([
            'codcli' => $this->codcli,
            'vlcredito' => $this->vlcredito,
            'dtbaixa' => $this->dtbaixa,
            'codprod' => $this->codprod,
        ]);
    }

    public function cadastrar(){

        $obDatabase = new Database('cadprod');
        $success = $obDatabase->insert([
            'descricao' => $this->descricao,
            'codsecao' => $this->codsecao,
            'und' => $this->und,
            'peso' => $this->peso,
            'qtestoque' => $this->qtestoque,
            'punit'=> str_replace(',','.',$this->punit),
            'pcompra' => str_replace(',','.',$this->pcompra)
        ]);

        return $success;

    }


    public static function buscar($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('credcli'))->select($where,$order,$limit,$filds);
    }

    public static function buscarcli($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('credcli'))->selectJoin($where,$order,$limit,$filds,'cadcli', 'credcli.codcli = cadcli.codcli');
    }
    public static function buscarprod($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('credcli'))->selectJoin($where,$order,$limit,$filds,'cadcli', 'credcli.codprod = cadprod.codprod');
    }
    public static function buscarJoin($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('cadprod'))->selectJoin($where,$order,$limit,$filds,'cadsecao', 'cadprod.codsecao = cadsecao.codsecao');
    }
}