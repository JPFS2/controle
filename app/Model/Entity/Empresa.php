<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class Empresa
{
    public $codempresa;
    public $razaosocial;
    public $fantasia;
    public $cnpj;
    public $ie;
    public $telefone;
    public $telefone2;
    public $email;
    public $cep;
    public $endereco;
    public $numero;
    public $bairro;
    public $cidade;
    public $uf;
    public $complemento;
    public $instagram;
    public $facebook;
    

    public function atualizar(){


        $obDatabase = new Database('cadempresa');
        $success = $obDatabase->update('codempresa = 1',[
            'razaosocial' => $this->razaosocial,
            'fantasia' => $this->fantasia,
            'cnpj' => $this->cnpj,
            'ie' => $this->ie,
            'telefone' => $this->telefone,
            'telefone2' => $this->telefone2,
            'cep' => $this->cep,
            'endereco' => $this->endereco,
            'numero' => $this->numero,
            'bairro' => $this->bairro,
            'cidade' => $this->cidade,
            'uf' => $this->uf,
            'complemento' => $this->complemento,
            'email' => $this->email,
            'instagram' => $this->instagram,
            'facebook' => $this->facebook
            
        ]);

    }

    public static function buscar(){
        return  (new Database('cadempresa'))->select(null,null,null,'*');
    }


}