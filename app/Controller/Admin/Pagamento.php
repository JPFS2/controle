<?php

namespace App\Controller\Admin;

use App\Controller\Pages\Page;
use App\Http\Request;
use App\Model\Entity\Pedido as M_Pedido;
use App\Model\Entity\PedidoItens as M_PedidoItens;
use App\Model\Entity\PedidoPagamento as M_PedidoPag;
use App\Model\Entity\Pagamento as M_Pagamento;
use App\Model\Entity\Produto as M_Produto;
use App\Model\Entity\mesa as M_Mesa;
use App\Utils\View;

class Pagamento extends Page
{


    /**
    * Metodo resposave por retornar o conteudo da view do Mesa
    *  @retunr string
    */
    public static function getMesa($request,$codmesa)
    {
        $content = View::render('pages/mesa', [
            'mesa' => $codmesa,
            'option' => self::getProdutoInsert(),
            'produtos' => self::getProduto($codmesa),
            'vlmesa' => self::getVlMesa($codmesa)

        ]);

        return parent::getPageLogin('Controle', $content);
    }

    /**
     * Metodo resposave por retornar o (option) listagem dos produtos para inserir
     *  @retunr string
     **/

    public static function addPMesa($request)
    {
        $postVars = $request->getPostVars();

        $pagamento = new M_Pagamento;
        $pagamento->codmesa = $postVars['codmesa'];
        $pagamento->valor = str_replace(',','.',$postVars['valor']);
        $pagamento->moeda = $postVars['ForaPag'];

        if((self::getVlMesa($pagamento->codmesa) - self::getPGMesa($pagamento->codmesa) - $pagamento->valor) < 0){
            header("Location: http://localhost/controle/admin/mesa/$pagamento->codmesa");
            die();
        }elseif((self::getVlMesa($pagamento->codmesa) - self::getPGMesa($pagamento->codmesa) - $pagamento->valor) == 0){
            $pagamento->cadastrarPmesa();

            $pedido = new M_Pedido();
            $pedido->codmesa = $pagamento->codmesa;
            $pedido->vltotal = self::getVlMesa($pagamento->codmesa);
            $pedido->codcli = $_SESSION['admin']['usuario']['id'];
            $numped = $pedido->cadastrar();

            $results = M_Mesa::buscarJoin("mesaitens.codmesa = $pedido->codmesa",null,null,'*');

            while ($produto = $results->fetchObject()){

                $pedidoitem = new M_PedidoItens();

                $pedidoitem->numped = $numped;
                $pedidoitem->codprod = $produto->codprod;
                $pedidoitem->qt = $produto->qt;
                $pedidoitem->punit = $produto->precounitario;
                $pedidoitem->vlliq = $produto->precototal;

                $pedidoitem->cadastrar();

            }

            $pagamentos = M_Pagamento::buscarPMesa();

            while ($pag = $pagamentos->fetchObject(M_Pagamento::class)){
                    $pedidoPag = new M_PedidoPag();
                    $pedidoPag->numped = $numped;
                    $pedidoPag->moeda = $pag->moeda;
                    $pedidoPag->valor = $pag->valor;

                    $pedidoPag->cadastrar();

            }

            M_Mesa::excluir($pedido->codmesa);
            M_Pagamento::excluir($pedido->codmesa);

            header("Location: http://localhost/controle/admin");
            die();

        }
        $pagamento->cadastrarPmesa();
        header("Location: http://localhost/controle/admin/mesa/$pagamento->codmesa");
        die();
    }
    public static function removePagamento($id)
    {
        $obCarrinho = M_Mesa::buscarItens("codmesaitem = $id")->fetchObject(M_Mesa::class);

        if($obCarrinho instanceof M_Mesa){
            $obCarrinho->excluirItem();
        }

        header("Location: http://localhost/controle/mesa/$obCarrinho->codmesa");
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

    public static function getPGMesa($codmesa)
    {
        $valor = M_Pagamento::buscarPMesa('codmesa = ' .$codmesa, null, null, 'SUM(valor) as valor')->fetchObject()->valor;

        if($valor == ''){
            $valor = '0.00';
        }

        return $valor;

    }




}