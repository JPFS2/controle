<?php

namespace App\Controller\Pages;

use App\Utils\View;

class Page
{
    /**
     * Metodo responsavel por rendereizar o header da pagina
     * @return string
     */
    public static function getHeader(){
        return View::render('pages/header',[
            'usuario' => $_SESSION['pages']['usuario']['nome']
        ]);
    }
    public static function getFooter(){
        return View::render('pages/footer');
    }

    public static function getPagination($request, $obPagination){
        // DESCOBRI QUANTAS PAGINAS TEM
        $pages = $obPagination->getPages();

        if(count($pages) <= 1) return '';

        $links = '';

        $url = $request->getRouter()->getCurrentUrl();

        $queryParams = $request->getQueryParams();

        foreach ($pages as $page){

            $queryParams['pg'] = $page['page'];

            $link = $url.'?'.http_build_query($queryParams);

            $links .= View::render('pages/pagination/link',[
                'link'=>$link,
                'page'=>$page['page'],
                'active' => $page['current'] ? 'active' : ''
            ]);
        }

        return View::render('pages/pagination/box',[
            'links'=>$links
        ]);


    }
    public static function getPage($title,$content ){
        return View::render('pages/page',[
            'title'=>$title,
            'header'=>self::getHeader(),
            'content'=>$content,
            'footer'=>self::getFooter()
        ]);
    }
    public static function getPageLogin($title ,$content ){
        return View::render('admin/page',[
            'title'=>$title,
            'header'=> '',
            'content'=>$content,
            'footer'=>'',
            'usuario' => isset($_SESSION['pages']['usuario']['nome']) ? $_SESSION['pages']['usuario']['nome'] : ''
        ]);
    }

}