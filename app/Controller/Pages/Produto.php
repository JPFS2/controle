<?php 

namespace App\Controller\Pages;
use App\Controller\Pages\Page;
use App\Http\Request;
use App\Model\Entity\Produto as M_Produto;
use App\Model\Entity\Secao;
use App\Utils\View;

class Produto extends Page {
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

            $secoes .= View::render('admin/produtos/secoes',[
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
    public static function getProduto($request,&$obPagination){
        $tr = '';

        // QUANTIDAD TOTAL DE REGISTROS

        $queryParams = $request->getQueryParams();

        $condicao = isset($queryParams['ds']) ? 'descricao like "%'.$queryParams['ds'].'%"': '';
        $results = M_Produto::buscar($condicao,null,null,'*');


        while ($produto = $results->fetchObject(M_Produto::class)){

            $tr .= View::render('pages/produtos/produto',[
                'codprod' => $produto->codprod,
                'descricao' => $produto->descricao,
                'qtestoque' => $produto->qtestoque,
                'punit' => str_replace('.',',',$produto->punit) ?? 'NÃO INFORMADO',
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



        $content = View::render('pages/produto',[
            'tr' => self::getProduto($request,$obPagination)
        ]);

        return parent::getPage('Controle',$content);
    }
    public static function addProduto($request){
       $content = View::render('admin/produto_add',[
           'secao'=> self::getSecao($request,0  )
       ]);

        return parent::getPage('Controle',$content);
    }

    public static function adicionaProduto($request){
        $postVars = $request->getPostVars();
        $obProduto = new M_Produto();

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
        $produto = M_Produto::buscar('codprod = '.$id,null,null,'*')->fetchObject(M_Produto::class);

        $content = View::render('admin/produto_form',[
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

        $obProduto = new M_Produto();

        $obProduto->codprod = $postVars['codprod'];
        $obProduto->descricao = $postVars['descricao'];
        $obProduto->codsecao = $postVars['codsecao'];
        $obProduto->und = $postVars['und'];
        $obProduto->peso = $postVars['peso'];
        $obProduto->qtestoque = $postVars['qtestoque'];
        $obProduto->pcompra = $postVars['pcompra'];
        $obProduto->punit = $postVars['punit'];

        $obProduto->atualizar();

        return self::getProdutos($request);
    }

}