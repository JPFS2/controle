<?php

namespace App\Controller\Admin;
use App\Controller\Admin\Page;
use App\Model\Entity\TabelaPreco as M_Tabelapreco;
use App\Utils\View;

class Tabelapreco extends Page {

    public static function getProduto(){
        $produtos = M_Tabelapreco::buscarProduto(null,null,null,'codprod,descricao');

        $option = '';
        while ($produto = $produtos->fetchObject()){

            $option .= View::render('admin/tabelapreco/produto',[
                'codigo' => $produto->codprod,
                'descricao' => $produto->descricao
            ]);

        }
        return $option;
    }
    public static function insereTabelaprecosItens($request,$id,$acao){

        $postVars = $request->getPostVars();

        $verificaCadastro = M_Tabelapreco::buscarItem('codprod = '.$postVars['codprod'].' and CodTab = '.$id,null,null,'Id');

        if(!empty($verificaCadastro)){
            M_Tabelapreco::atualizaItem($verificaCadastro['Id'],$postVars);
        }else{
            M_Tabelapreco::insereItem($postVars);
        }

        header("Location: http://localhost/comgelo/admin/tabelapreco/$id/edit");
        die();
    }
    public static function getTabelaprecoitens($id){

        $tr = '';


        $results = M_Tabelapreco::buscarItens('CodTab = '.$id,'codprod',null,'tabprecositens.*, cadprod.descricao');

        while ($tabelaitens = $results->fetchObject()) {

            $tr .= View::render('admin/tabelapreco/tabelaprecoitens', [
                'codigo' => $tabelaitens->codprod,
                'descricao' => $tabelaitens->descricao,
                'valor' => $tabelaitens->preco,
                'id' => $tabelaitens->Id,


            ]);
        }

        return $tr;
    }

    public static function deleteTabelaprecoiten($request,$id){

        $queryParams = $request->getQueryParams();
        $result = M_Tabelapreco::buscarItens('Id = '.$queryParams['item'],null,null,'tabprecositens.*, cadprod.descricao');

        $tabelaitem = $result->fetchObject();

        M_Tabelapreco::deletaItem('Id = '.$tabelaitem->Id);
        header("Location: http://localhost/controle/admin/tabelapreco/$id/edit");
        die();

    }

    public static function getTabelapreco($request){
        $tr = '';

        $results = M_Tabelapreco::buscar(null,null,null,'*');
        while ($tabela = $results->fetchObject(M_Tabelapreco::class)){


            $tr .= View::render('admin/tabelapreco/tabelapreco',[
                'codtabela' => $tabela->CodTab,
                'descricao' => $tabela->Descricao,
                'pervariacao' => $tabela->PercVariacao,
                'qtestoque' => 'qtestoque'
            ]);

        }

        return $tr;
    }

    public static function getTabelaprecos($request){


        $content = View::render('admin/tabelapreco',[
            'title' => '',
            'tr' => self::getTabelapreco($request)
        ]);

        return parent::getPage('Controle',$content);
    }

    public static function editTabelaprecos($request,$id,$acao){

        if ($acao == 'excluir'){
            $queryParams = $request->getQueryParams();
            $result = M_Tabelapreco::buscarItens('Id = '.$queryParams['item'],null,null,'tabprecositens.*, cadprod.descricao');

            $tabelaitem = $result->fetchObject();
            M_Tabelapreco::deletaItem('Id = '.$tabelaitem->Id);
            header("Location: http://localhost/controle/admin/tabelapreco/$id/edit");
            die();
        }

        $tabela = M_Tabelapreco::buscar('codtab = '.$id,null,null,'*')->fetchObject(M_Tabelapreco::class);

        $content = View::render('admin/tabelapreco_form',
            [
                'codigo' => $tabela->CodTab,
                'descricao' => $tabela->Descricao,
                'valor' => $tabela->PercVariacao,
                'tr' => self::getTabelaprecoitens($id),
                'option' => self::getProduto(),
                'codtab' => $id

            ]);

        return parent::getPage('Controle',$content);

    }
}