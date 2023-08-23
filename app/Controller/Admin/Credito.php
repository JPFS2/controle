<?php 

namespace App\Controller\Admin;
use App\Controller\Admin\Page;
use App\Http\Request;
use App\Model\Entity\Credito as M_Credito;
use App\Model\Entity\Secao;
use App\Utils\View;

class Credito extends Page {
    /*
    * Metodo resposave por retornar o conteudo da view do home
    *  @retunr string 
    */

    public static function getSecao($request,$id){
        $secoes = '';
        $results = Secao::buscar(null,null,null,'*');

        $selectd = '';

        while ($secao = $results->fetchObject(Secao::class)){

            if ($id == $secao->codsecao){
                $selectd = 'selected="selected"';
            }else {
                $selectd = '';
            }

            $secoes .= View::render('admin/credito/secoes',[
                'selectd' => $selectd ,
                'codsecao' => $secao->codsecao,
                'secao' => $secao->secao
            ]);


        }

        return $secoes;
    }

    /**
     * Responsavel por buscar cada produto e montar a tela de produtos
     * @param Request $request
     * @param $obPagination
     * @return string
     */
    public static function getCredito($request,&$obPagination){
        $tr = '';

        // QUANTIDAD TOTAL DE REGISTROS

        $queryParams = $request->getQueryParams();

        $condicao = isset($queryParams['ds']) ? 'codcli like "%'.$queryParams['ds'].'%"': '';
        $results = M_Credito::buscar($condicao,null,null,'*');


        while ($credito = $results->fetchObject(M_Credito::class)){

            $tr .= View::render('admin/credito/produto',[
                'codcred' => $credito->codcred,
                'codcli' => $credito->codcli,
                'codprodc' => $credito->codprodc,
                'dtbaixa' => $credito->dtbaixa,
                'vlcredito' => $credito->vlcredito ?? 'NÃO INFORMADO',
                'status' => 'Status'
            ]);

        }

        return $tr;
    }

    /**
     * Realiza a Chamada da tela de produtos
     * @param $request
     * @return string
     */
    public static function getProdutos($request){



        $content = View::render('admin/credito',[
            'tr' => self::getCredito($request,$obPagination)
        ]);

        return parent::getPage('Controle',$content);
    }
    public static function addProduto($request){
       $content = View::render('admin/credito_add',[
           'secao'=> self::getSecao($request,0  )
       ]);

        return parent::getPage('Controle',$content);
    }

    public static function adicionaProduto($request){
        $postVars = $request->getPostVars();
        $obProduto = new M_Credito();

        $obProduto->descricao = $postVars['descricao'];
        $obProduto->codsecao = $postVars['codsecao'];
        $obProduto->und = $postVars['und'];
        $obProduto->peso = $postVars['peso'];
        $obProduto->qtestoque = $postVars['qtestoque'];
        $obProduto->pcompra = $postVars['pcompra'];
        $obProduto->punit = $postVars['punit'];

        $obProduto->cadastrar();

        return self::getProdutos($request);
    }

    /**
     * Responsavel por buscar o produto especifico e mostrar na tela
     * @param $request
     * @param int $id (codigo do produto)
     * @return string
     */
    public static function editProdutos($request,$id){
        $produto = M_Credito::buscar('codprod = '.$id,null,null,'*')->fetchObject(M_credito::class);

        $content = View::render('admin/credito_form',[
            'codigo' => $produto->codprod,
            'descricao' => $produto->descricao,
            'und' => $produto->und,
            'peso' => $produto->peso,
            'pcompra' => $produto->pcompra,
            'punit' => $produto->punit,
            'qtestoque' => $produto->qtestoque,
            'secao'=> self::getSecao($request,$produto->codsecao)


        ]);

        return parent::getPage('Controle',$content);
    }

    /**
     * Responsavel por atualizar os dados de um determinado produto
     * @param Request $request
     * @return string
     */
    public static function atualizaProduto($request){
        $postVars = $request->getPostVars();

        $obProduto = new M_Credito();
        $obProduto->codprod = $postVars['codprod'];
        $obProduto->descricao = $postVars['descricao'];
        $obProduto->codsecao = $postVars['codsecao'];
        $obProduto->und = $postVars['und'];
        $obProduto->peso = $postVars['peso'];
        $obProduto->qtestoque = $postVars['estoque'];
        $obProduto->pcompra = $postVars['pcompra'];
        $obProduto->punit = $postVars['punit'];

        $obProduto->atualizar();

        return self::getProdutos($request);
    }

}