<?php

namespace App\Http\Middleware;

use App\Http\Request;
use \App\Session\Admin\Login as SessionAdminLogin;
class RequireAdminLogin
{
    /**
     * @param Request $request
     * @param $next
     * @return void
     */
    public function handle($request, $next){
        if(!SessionAdminLogin::isLogged()){
            $request->getRouter()->redirect('/admin/login');
        }
        return $next($request);
    }

}