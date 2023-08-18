<?php

namespace App\Http\Middleware;

use App\Http\Request;
use \App\Session\Pages\Login as SessionPagesLogin;
class RequirePagesLogout
{
    /**
     * @param Request $request
     * @param $next
     * @return void
     */
    public function handle($request, $next){
        if(SessionPagesLogin::isLogged()){
            $request->getRouter()->redirect('/');
        }
        return $next($request);
    }

}