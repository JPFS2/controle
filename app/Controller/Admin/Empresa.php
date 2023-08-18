<?php 

namespace App\Controller\Admin;
use App\Controller\Admin\Page;
use App\Model\Entity\Empresa as Mempresa;
use App\Utils\View;

class Empresa extends Page {
    /*
    * Metodo resposave por retornar o conteudo da view do home
    *  @retunr string 
    */    
    public static function getEmpresa(){
        $obEmpresa = new Mempresa();
        $result = $obEmpresa::buscar();

        $itens = $result->fetchObject(Mempresa::class);

        $content = View::render('admin/empresa', [
            'razaosocial'=>   $itens->razaosocial,
            'fantasia' => $itens->fantasia,
            'cnpj' => $itens->cnpj,
            'ie' => $itens->ie,
            'im' => '',
            'telefone' => $itens->telefone,
            'telefone2' => $itens->telefone2,
            'cep' => $itens->cep,
            'endereco' => $itens->endereco,
            'numero' => $itens->numero,
            'bairro' => $itens->bairro,
            'cidade' => $itens->cidade,
            'uf' => $itens->uf,
            'complemento' => $itens->complemento,
            'email' => $itens->email,
            'instagram' => $itens->instagram,
            'facebook' => $itens->facebook,

        ]);

        return parent::getPage('Controle',$content);
    }

    public static function atualizaEmpresa($request){

        $postVars = $request->getPostVars();

        $obEmpresa = new Mempresa();
        $obEmpresa->codempresa = $postVars['codempresa'];
        $obEmpresa->razaosocial = $postVars['razaosocial'];
        $obEmpresa->fantasia = $postVars['fantasia'];
        $obEmpresa->cnpj = $postVars['cnpj'];
        $obEmpresa->ie = $postVars['ie'];
        $obEmpresa->cep = $postVars['cep'];
        $obEmpresa->telefone = $postVars['telefone'];
        $obEmpresa->telefone2 = $postVars['telefone2'];
        $obEmpresa->endereco = $postVars['endereco'];
        $obEmpresa->numero = $postVars['numero'];
        $obEmpresa->bairro = $postVars['bairro'];
        $obEmpresa->cidade = $postVars['cidade'];;
        $obEmpresa->uf = $postVars['uf'];
        $obEmpresa->complemento = $postVars['complemento'];
        $obEmpresa->email = $postVars['email'];
        $obEmpresa->instagram = $postVars['instagram'];
        $obEmpresa->facebook = $postVars['facebook'];

        $obEmpresa->atualizar();

        return self::getEmpresa();
    }
}

