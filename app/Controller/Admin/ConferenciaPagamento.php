<?php 

namespace App\Controller\Admin;
use App\Controller\Admin\Page;
use App\Model\Entity\PedidoPagamento as M_PedidoPag;
use App\Model\Entity\Cliente as M_Cliente;
use App\Model\Entity\Pedido as M_Pedido;
use App\Utils\View;

class ConferenciaPagamento extends Page {
    /*
    * Metodo resposave por retornar o conteudo da view do home
    *  @retunr string 
    */
    public static function getPagamentoCaixa(){

        $content = View::render('admin/conferencia_caixa',[
            'mesa' => self::getPagamentofunc(),
        ]);

        return parent::getPage('Controle',$content);
    }

    public static function getPagamentofunc(){
        $tr = '';

        $funcs = M_Cliente::buscarComPedido('pedido.numped is not null  and pedido.cxencerrado is null  group by cadcliente.codcli',
            null,
            null,
            'cadcliente.codcli, cadcliente.cliente, pedido.numped, pedido.cxencerrado '
        );

        while ($func = $funcs->fetchObject()){

            $tr .= View::render('admin/conferenciacaixa/garcons',[
                'nome' => substr($func->cliente,0,10),
                'pags' => self::getPags($func->codcli),
                'cod' => $func->codcli
            ]);
        }

        return $tr;
    }

    public static function getPags($func){

        $tr = '';
        $pagamentos = M_PedidoPag::buscarPagPedidos('pedido.cxencerrado is null and pedido.codcli = '.$func.' group by moeda',null,null,
            'moeda, sum(valor) as valor');

        while ($pagamento = $pagamentos->fetchObject()){

            $tr .= View::render('admin/conferenciacaixa/pags',[
                'moeda' => $pagamento->moeda,
                'valor' => round($pagamento->valor,2)
            ]);
        }

        return $tr;
    }

    public static function getRecebimentoFuncionario($codigo){
        $tr = '';
        $pedidos = M_PedidoPag::buscarPagPedidos('pedido.codcli = '.$codigo.' and cxencerrado is null'
            ,null,null,'*');



        $garcon = M_Cliente::buscar('codcli = '.$codigo)->fetchObject();

        while ($pedido = $pedidos->fetchObject()){

            $date = date_create($pedido->dtpedido);

            $tr .= View::render('admin/conferenciacaixa/pedidos', [
                'numped' => $pedido->numped,
                'mesa' =>  $pedido->codmesa,
                'moeda' =>  $pedido->moeda,
                'dtpedido' => date_format($date,'d/m/Y - H:m'),
                'vlliq' => $pedido->valor,

            ]);
        }

        $content = View::render('admin/conferencia_detahes', [
            'tr' => $tr,
            'usuario' => $_SESSION['admin']['usuario']['nome'],
            'garcon' => ucfirst(strtolower($garcon->cliente)),
            'codgarcon' => $garcon->codcli

        ]);

        return parent::getPageLogin('Controle',$content);
    }
    public static function confirmaRecebientoFuncionario($id){

        M_Pedido::atualizar($id);

        header("Location: http://localhost/controle/admin/conferenciacaixa");
        die();

    }

}