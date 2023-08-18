<?php

namespace App\Controller\Admin;
use App\Controller\Admin\Page;
use App\Model\Entity\Cliente as M_Cliente;
use App\Utils\View;
use WilliamCosta\DatabaseManager\Pagination;

class Cliente extends Page {


    public static function getCliente($request,&$obPagination){
        $tr = '';

        // QUANTIDAD TOTAL DE REGISTROS



        // Pagina atual
        $queryParams = $request->getQueryParams();

        $paginaAtual = $queryParams['pg'] ?? 1;
        $condicao = isset($queryParams['cl']) ? 'cliente like "%'.$queryParams['cl'].'%"': '';

        $quantidadetotal = M_Cliente::buscar($condicao,null,null,'COUNT(*) as qtd')->fetchObject()->qtd;

        $obPagination = new Pagination($quantidadetotal,$paginaAtual,30);

        $results = M_Cliente::buscar($condicao,null,$obPagination->getLimit(),'*');

        while ($cliente = $results->fetchObject(M_Cliente::class)){

          $tr .= View::render('admin/clientes/cliente',[
              'codigo' => $cliente->codcli,
              'cliente' => $cliente->cliente,
              'telefone' => $cliente->telefone ?? 'NÃO INFORMADO',
              'email' => $cliente->email,
              'status' => 'Status'
          ]);

        }

        return $tr;
    }
    /*
    * Metodo resposave por retornar o conteudo da view do home
    *  @retunr string 
    */    
    public static function getClientes($request){

        $content = View::render('admin/cliente',[
            'tr' => self::getCliente($request,$obPagination),
            'pagination' => parent::getPagination($request, $obPagination)
        ]);

        return parent::getPage('Controle',$content);
    }

    public static function editClientes($request,$id){


        $cliente = M_Cliente::buscar('codcli = '.$id,null,null,'*')->fetchObject(M_Cliente::class);

        $content = View::render('admin/cliente_form',[
            'codigo' => $cliente->codcli,
            'cliente' => $cliente->cliente,
            'fantasia' => $cliente->fantasia ?? 'NÃO INFORMADO',
            'cnpj' => $cliente->cnpj ?? 'NÃO INFORMADO',
            'ie' => $cliente->ie ?? 'NÃO INFORMADO',
            'cpf' => $cliente->cpf ?? 'NÃO INFORMADO',
            'telefone' => $cliente->telefone ?? 'NÃO INFORMADO',
            'telefone2' => $cliente->telefone2 ?? 'NÃO INFORMADO',
            'cep' => $cliente->cep ?? 'NÃO INFORMADO',
            'endereco' => $cliente->endereco ?? 'NÃO INFORMADO',
            'numero' => $cliente->numero ?? 'NÃO INFORMADO',
            'bairro' => $cliente->bairro ?? 'NÃO INFORMADO',
            'cidade' => $cliente->cidade ?? 'NÃO INFORMADO',
            'uf' => $cliente->uf ?? 'NÃO INFORMADO',
            'complemento' => $cliente->complemento ?? 'NÃO INFORMADO',
            'email' => $cliente->email ?? 'NÃO INFORMADO',
            'cidade' => $cliente->cidade ?? 'NÃO INFORMADO',
            'instagram' => $cliente->instagram ?? 'NÃO INFORMADO',
            'facebook' => $cliente->facebook ?? 'NÃO INFORMADO',
            'senha' => $cliente->senha ?? 'NÃO INFORMADO',

        ]);

        return parent::getPage('Controle',$content);
    }

    public static function atualizaClientes($request){

        $postVars = $request->getPostVars();

        $obCliente = new M_Cliente();
        $obCliente->codcli = $postVars['codcli'];
        $obCliente->cliente = $postVars['cliente'];
        $obCliente->fantasia = $postVars['fantasia'];
        $obCliente->cpf = $postVars['cpf'];
        $obCliente->senha = $postVars['senha'];
        $obCliente->cep = $postVars['cep'];
        $obCliente->telefone = $postVars['telefone'];
        $obCliente->telefone2 = $postVars['telefone2'];
        $obCliente->endereco = $postVars['endereco'];
        $obCliente->numero = $postVars['numero'];
        $obCliente->bairro = $postVars['bairro'];
        $obCliente->cidade = $postVars['cidade'];;
        $obCliente->uf = $postVars['uf'];
        $obCliente->complemento = $postVars['complemento'];
        $obCliente->email = $postVars['email'];

        $obCliente->atualizar();

        return self::getClientes($request);
    }

    public static function cadastraCliente($request){

        $postVars = $request->getPostVars();

        $obCliente = new M_Cliente();
        $obCliente->cliente = $postVars['cliente'];
        $obCliente->fantasia = $postVars['fantasia'];
        $obCliente->cpf = $postVars['cpf'];
        $obCliente->senha = $postVars['senha'];
        $obCliente->cep = $postVars['cep'];
        $obCliente->telefone = $postVars['telefone'];
        $obCliente->telefone2 = $postVars['telefone2'];
        $obCliente->endereco = $postVars['endereco'];
        $obCliente->numero = $postVars['numero'];
        $obCliente->bairro = $postVars['bairro'];
        $obCliente->cidade = $postVars['cidade'];;
        $obCliente->uf = $postVars['uf'];
        $obCliente->complemento = $postVars['complemento'];
        $obCliente->email = $postVars['email'];

        $obCliente->cadastrar();

        return self::getClientes($request);
    }



    public static function insereCliente($request){

        $content = View::render('admin/cliente_add',[]);

        return parent::getPage('Controle',$content);
    }


}