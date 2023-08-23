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
 public $codcred;
 public $vlcredito = 0;
 public $codprodc;
 public $codcli;
 public $dtbaixa;




    public function atualizar(){

        $obDatabase = new Database('cadprod');
        $success = $obDatabase->update('codprod ='.$this->codprod,[
            'descricao' => $this->descricao,
            'codsecao' => $this->codsecao,
            'und' => $this->und,
            'peso' => $this->peso,
            'qtestoque' => $this->qtestoque,
            'punit'=> str_replace(',','.',$this->punit),
            'pcompra' => str_replace(',','.',$this->pcompra)
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
    public function cadastrarcred(){

        $obDatabase = new Database('credcli');
        $success = $obDatabase->insert([
            'codcred ' => $this->codcred,
            'codcli' => $this->codcli,
            'codprod' => $this->codprod,
            'vlcredito'=> str_replace(',','.',$this->vlcredito),
            'dtbaixa' => $this->dtbaixa
        ]);

        return $success;

    }

    public static function buscar($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('cadprod'))->select($where,$order,$limit,$filds);
    }

    public static function buscarJoin($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('cadprod'))->selectJoin($where,$order,$limit,$filds,'cadsecao', 'cadprod.codsecao = cadsecao.codsecao');
    }

    public static function buscarC($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('credcli'))->select($where,$order,$limit,$filds);
    }

    public static function buscarCJoin($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('credcli'))->selectJoin($where,$order,$limit,$filds,'cadcliente', 'credcli.codcli = cadcliente.codcli');
    }


}