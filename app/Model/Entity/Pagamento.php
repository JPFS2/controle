<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class Pagamento
{

    public $codigo;
    public $moeda;
    public $valor;
    public $codmesa;


    public function atualizar(){

        $obDatabase = new Database('mesa');
        $success = $obDatabase->update('codcar ='.$this->codcar,[
            'codprod' => $this->codprod,
            'punit' => $this->punit,
            'qt' => $this->qt,
            'vlliq' => ($this->qt * $this->punit),
            'dtinclusao' => 'CURRENT_DATE()',
            'codcli' => $this->codcli
        ]);

    }

    public function cadastrarPmesa(){

        $obDatabase = new Database('mesapagamentos  ');
        $id = $obDatabase->insert([
            'codmesa' => $this->codmesa,
            'moeda' => $this->moeda,
            'valor' => $this->valor
        ]);

        return $id;
    }

    public static function excluir($codmesa){
        $obDatabase = new Database('mesapagamentos');
        $success = $obDatabase->delete('codmesa = '.$codmesa);
    }

    public function excluirItem(){
        $obDatabase = new Database('mesapagamentos');
        $success = $obDatabase->delete('codigo = '.$this->codigo);
    }

    public static function buscarPMesa($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('mesapagamentos'))->select($where,$order,$limit,$filds);
    }

    public static function buscarItens($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('mesaitens'))->select($where,$order,$limit,$filds);
    }

    public static function buscarJoin($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('mesaitens'))->selectJoin($where,$order,$limit,$filds,'cadprod', 'cadprod.codprod = mesaitens.codprod');
    }


}