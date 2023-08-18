<?php

namespace App\Controller\Pages;

use App\Controller\Pages\Page;
use App\Http\Request;
use App\Controller\Pages\Pagamento;

use App\Model\Entity\Pagamento as M_Pagamento;
use App\Model\Entity\Produto as M_Produto;
use App\Model\Entity\mesa as M_Mesa;
use App\Model\Entity\MesaItens as M_MesaItem;
use App\Model\Entity\MesaPagamento as M_MesaPag;
use App\Utils\View;

class Mesa extends Page
{


    /**
    * Metodo resposave por retornar o conteudo da view do Mesa
    *  @retunr string
    */
    public static function getMesa($request,$codmesa)
    {
        $content = View::render('pages/mesa', [
            'usuario' => $_SESSION['pages']['usuario']['nome'],
            'mesa' => $codmesa,
            'option' => self::getProdutoInsert(),
            'produtos' => self::getProduto($codmesa)    ,
            'vlmesa' => self::getVlMesa($codmesa),
            'vlpendente' => (self::getVlMesa($codmesa) - self::getPGMesa($codmesa)),
            'pagos' => self::getPago($codmesa)

        ]);

        return parent::getPageLogin('Controle', $content);
    }

    /**
     * Metodo resposave por retornar os produtos inseridos na mesa
     *  @retunr string
     **/
    public static function getProduto($codmesa){

        $tr = '';
        $results  = M_MesaItem::buscarItens('codmesa = '.$codmesa,null,null,'*');

        while ($itemMesa = $results->fetchObject()){

            $tr .= View::render('pages/mesa/produto',[
                'codmesaitem' => $itemMesa->codmesaitem,
                'codprod' => $itemMesa->codprod,
                'descricao' => $itemMesa->descricao,
                'punit' => $itemMesa->precounitario ?? 'NÃO INFORMADO',
                'qtd' => $itemMesa->qt ?? 'NÃO INFORMADO',
                'vlliq' => $itemMesa->precototal,
            ]);
        }
        return $tr;
    }

    /**
     * Metodo Responsavel por trazer as formas de pagamento já inseridas
     * @param int $codmesa
     * @return string
     */
    public static function getPago($codmesa){

        $tr = '';
        $results = M_Pagamento::buscarPMesa('codmesa = '.$codmesa);

        while ($pago = $results->fetchObject()){

            $tr .= View::render('pages/mesa/pagamento',[
                'moeda' => $pago->moeda,
                'valor' => $pago->valor,
                'codpag' => $pago->codigo
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


            $tr .= View::render('pages/mesa/produto_insert',[
                'codigo' => $produto->codprod,
                'descricao' => $produto->descricao,
                'preco' => $produto->punit ?? 'NÃO INFORMADO',
            ]);
        }

        return $tr;

    }

    public static function addProduto($request)
    {
        $postVars = $request->getPostVars();

        $produto = explode('-',$postVars['codigo']) ;

        $mesa = new M_MesaItem();
        $mesa->codprod = array_shift($produto);
        $mesa->precounitario = array_pop($produto);
        $mesa->qt = $postVars['qt'];
        $mesa->codmesa = $postVars['mesa'];

        $mesa->cadastrar();

        header("Location: http://localhost/controle/mesa/$mesa->codmesa");
        die();
    }
    public static function removeMoeda($id)
    {
        $obCarrinho = M_Pagamento::buscarPMesa("codigo = $id")->fetchObject(M_Pagamento::class);

        if($obCarrinho instanceof M_Pagamento){
            $obCarrinho->excluirItem();
        }

        header("Location: http://162.214.207.127/~johnsousa/controle/mesa/$obCarrinho->codmesa");
        die();
    }

    /** Recebe o numero da mesa da tela do Dashboard e direciona para mesa especifica */
    public static function buscaMesa($request)
    {
        $postVars = $request->getPostVars();
        header("Location: http://localhost/controle/mesa/".$postVars['mesa']);
        die();
    }

    /** Pega o Valor consumido da Mesa */
    public static function getVlMesa($codmesa)
    {
        $valor = M_MesaItem::buscar('codmesa = '.$codmesa,null,null,'sum(precototal) as qt')->fetchObject()->qt;

        if($valor == ''){
            $valor = '0.00';
        }
        return $valor;

    }

    /** Pega o Valor Pago da Mesa */
    public static function getPGMesa($codmesa)
    {
        $valor = M_MesaPag::buscar('codmesa = '.$codmesa,null,null,'sum(valor) as qt')->fetchObject()->qt;

        if($valor == ''){
            $valor = 0;
        }
        return $valor;

    }



}