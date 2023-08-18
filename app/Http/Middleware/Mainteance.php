<?php

namespace App\Http\Middleware;

use App\Http\Response;
use http\Env\Request;

class Mainteance
{

    /**
     * Metodo Responsavel por executar os Middlewares
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, $next){
        //Verifica o Estado de manutenção da pagina
        if(getenv('MAINTENANCE') == 'true'){
            throw new \Exception("Pagina em Manutenção",200);
        }
        // executa o proximo nivel do MiddlewareW
        return $next($request);
    }

}