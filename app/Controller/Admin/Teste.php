<?php 

namespace App\Controller\Admin;
use App\Controller\Admin\Page;
use App\Model\Entity\TaxaEntrega as M_TaxaEntrega;
use App\Utils\View;

class Teste extends Page {

    public static function getTaxaEntrega($request){
        $tr = '';

        // QUANTIDAD TOTAL DE REGISTROS

        $results = M_TaxaEntrega::buscar(null,null,null,'*');

        while ($taxa = $results->fetchObject(M_TaxaEntrega::class)){


            $tr .= View::render('admin/taxaentrega/taxaentrega',[
                'codtaxa' => $taxa->codtaxa,
                'descricao' => $taxa->Descricao,
                'valor' => $taxa->valor ?? 'NÃO INFORMADO'
            ]);

        }

        return $tr;
    }


    /*
    * Metodo resposave por retornar o conteudo da view do home
    *  @retunr string 
    */

    public static function getTaxasEntrega($request){

        $content = View::render('admin/taxaentrega',[
            'title' => '',
            'tr' => self::getTaxaEntrega($request)
        ]);

        return parent::getPage('Controle',$content);
    }

    public static function editTaxasEntrega($request,$id,$acao){


        if($acao == 'edit'){


            $cliente = M_TaxaEntrega::buscar('codtaxa = '.$id,null,null,'*')->fetchObject(M_TaxaEntrega::class);

            $content = View::render('admin/taxaentrega_form',
                [
                    'codigo' => $cliente->codtaxa,
                    'descricao' => $cliente->Descricao,
                    'valor' => $cliente->valor
                ]);

            return parent::getPage('Controle',$content);

        }else{
            echo 'não é edit';
        }


    }
    public static function atualizaTaxasEntrega($request){
        $postVars = $request->getPostVars();

        $taxaEntrega = new M_TaxaEntrega();
        $taxaEntrega->codtaxa = $postVars['codtaxa'];
        $taxaEntrega->Descricao = $postVars['Descricao'];
        $taxaEntrega->valor = $postVars['valor'];

        $taxaEntrega->atualizar();

        return self::getTaxasEntrega($request);
    }



}