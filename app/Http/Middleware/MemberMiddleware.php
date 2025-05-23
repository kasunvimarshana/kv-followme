<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response;
//use \Response;
use App\Login;

class MemberMiddleware
{
    public function handle($request, Closure $next)
    {
        /*if(!Auth::check()){
            return redirect('/login');
        }*/
        if( (!Login::isLogin()) ){
            return redirect('/login');
        }
        return $next($request);
    }
}
