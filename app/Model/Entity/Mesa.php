<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class mesa
{

    public $codmesaitem;
    public $codmesa;
    public $codprod;
    public $qt;
    public $precounitario;
    public $precototal;




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

    public function cadastrarItem(){

        $obDatabase = new Database('mesaitens');
        $id = $obDatabase->insert([
            'codmesa' => $this->codmesa,
            'codprod' => $this->codprod,
            'qt' => $this->qt,
            'precounitario' => $this->precounitario,
            'precototal' => ($this->qt * $this->precounitario),

        ]);

        return $id;
    }

    public static function excluir($codmesa){
        $obDatabase = new Database('mesaitens');
        $success = $obDatabase->delete('codmesa = '.$codmesa);
    }

    public function excluirItem(){
        $obDatabase = new Database('mesaitens');
        $success = $obDatabase->delete('codmesaitem = '.$this->codmesaitem);
    }


    public static function buscar($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('mesa'))->select($where,$order,$limit,$filds);
    }

    public static function buscarItens($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('mesaitens'))->select($where,$order,$limit,$filds);
    }

    public static function buscarJoin($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('mesaitens'))->selectJoin($where,$order,$limit,$filds,'cadprod', 'cadprod.codprod = mesaitens.codprod');
    }


}