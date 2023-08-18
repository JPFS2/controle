<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class Cliente
{
 public $codcli;
 public $cnpj;
 public $ie;
 public $cpf;
 public $cliente;
 public $fantasia;
 public $endereco;
 public $numero;
 public $bairro;
 public $cidade;
 public $uf;
 public $cep;
 public $importado;
 public $telefone;
 public $telefone2;
 public $complemento;
 public $email;
 public $senha;
 public $instagram;
 public $facebook;
 public $Prazo;
 public $Bloqueado;
 public $codexterno;
 public $codtabela;
 public $Ativo;


    public function atualizar(){

        $obDatabase = new Database('cadcliente');
        $success = $obDatabase->update('codcli ='.$this->codcli,[
            'cliente' => $this->cliente,
            'fantasia' => $this->fantasia,
            'cpf'=> $this->cpf,
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
            'senha' => $this->senha,
        ]);

    }

    public function cadastrar(){

        $obDatabase = new Database('cadcliente');
        $id = $obDatabase->insert([
            'cliente' => $this->cliente,
            'fantasia' => $this->fantasia,
            'senha' => $this->senha,
            'cpf'=> $this->cpf,
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
        ]);

        return $id;
    }

    public static function buscar($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('cadcliente'))->select($where,$order,$limit,$filds);
    }

    public static function buscarComPedido($where = null, $order = null, $limit = null, $filds = '*'){
        return  (new Database('cadcliente'))->selectJoin($where,$order,$limit,$filds,'pedido', 'cadcliente.codcli = pedido.codcli');
    }

    public static function getUserByEmail($email){
        return  (new Database('cadcliente'))->select('email = "'.$email.'"')->fetchObject(self::class);
    }


}