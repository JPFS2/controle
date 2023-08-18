<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Page;
use App\Http\Request;
use App\Controller\Admin\Pagamento;
use App\Model\Entity\Pedido as M_Pedido;
use App\Model\Entity\Produto as M_Produto;
use App\Model\Entity\mesa as M_Mesa;
use App\Utils\View;

class Mesa extends Page
{


    /**
    * Metodo resposave por retornar o conteudo da view do Mesa
    *  @retunr string
    */
    public static function getMesa($request,$codmesa)
    {
        $content = View::render('admin/mesa', [
            'mesa' => $codmesa,
            'option' => self::getProdutoInsert(),
            'produtos' => self::getProduto($codmesa),
            'vlmesa' => self::getVlMesa($codmesa),
            'vlpendente' => (self::getVlMesa($codmesa) - Pagamento::getPGMesa($codmesa))

        ]);

        return parent::getPageLogin('Controle', $content);
    }

    /**
     * Metodo resposave por retornar os produtos inseridos na mesa
     *  @retunr string
     **/
    public static function getProduto($codmesa){

        $tr = '';
        $results = M_Mesa::buscarJoin("mesaitens.codmesa = $codmesa",null,null,'*');

        while ($produto = $results->fetchObject()){


            $tr .= View::render('admin/mesa/produto',[
                'codmesaitem' => $produto->codmesaitem,
                'codprod' => $produto->codprod,
                'descricao' => $produto->descricao,
                'punit' => $produto->precounitario ?? 'NÃO INFORMADO',
                'qtd' => $produto->qt ?? 'NÃO INFORMADO',
                'vlliq' => $produto->precototal,
            ]);
        }
        return $tr;
    }

    /**
     * Metodo resposave por retornar o (option) listagem dos produtos para inserir
     *  @retunr string
     **/
    public static function getProdutoInsert(){

        $tr = '';

        $results = M_Produto::buscar(null,null,null,'*');

        while ($produto = $results->fetchObject()){


            $tr .= View::render('admin/mesa/produto_insert',[
                'codigo' => $produto->codprod,
                'descricao' => $produto->descricao,
                'peso' => $produto->peso,
                'preco' => $produto->punit ?? 'NÃO INFORMADO',
            ]);
        }

        return $tr;

    }
    public static function addProduto($request)
    {
        $postVars = $request->getPostVars();

        $produto = explode('-',$postVars['codigo']) ;

        $mesa = new M_Mesa();
        $mesa->codprod = array_shift($produto);
        $mesa->precounitario = array_pop($produto);
        $mesa->qt = $postVars['qt'];
        $mesa->codmesa = $postVars['mesa'];

        $mesa->cadastrarItem();

        header("Location: http://localhost/controle/admin/mesa/$mesa->codmesa");
        die();
    }
    public static function removeProduto($id)
    {
        $obCarrinho = M_Mesa::buscarItens("codmesaitem = $id")->fetchObject(M_Mesa::class);

        if($obCarrinho instanceof M_Mesa){
            $obCarrinho->excluirItem();
        }

        header("Location: http://localhost/controle/admin/mesa/$obCarrinho->codmesa");
        die();
    }

    public static function getVlMesa($codmesa)
    {
        $valor = M_Mesa::buscarItens('codmesa = ' .$codmesa, null, null, 'SUM(precototal) as valor')->fetchObject()->valor;

        if($valor == ''){
            $valor = '0.00';
        }

        return $valor;

    }




}