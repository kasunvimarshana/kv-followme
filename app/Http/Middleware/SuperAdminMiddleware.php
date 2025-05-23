<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Auth;
//use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response;
//use \Response;
use App\Login;
use App\User;
use App\UserRole;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    /*public function handle($request, Closure $next)
    {
        if ($request->user() && $request->user()->type != ‘super_admin’)
        {
            return new Response(view(‘unauthorized’)->with(‘role’, ‘SUPER ADMIN’));
        }
        return $next($request);
    }*/
    /*public function handle($request, Closure $next)
    {
        if(!Auth::check()){
            return redirect('/');
        }

        if(!Auth::user()->access){
            return redirect('dashboard');
        }
        $user = Auth::user();
        return $next($request)->with('user', $user);
    }*/
    /*public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::guard($guard)->check()) {
            return redirect('/login');
        }
        return $next($request);
    }*/
    
    public function handle($request, Closure $next)
    {
        if( (!Login::isLogin()) ){
            return redirect('/login');
        }
        $loginUser = Login::getUserData();
        $loginUserRole = new UserRole();
        $hasRole = $loginUserRole->where('user_pk','=',$loginUser->mail)
            ->where('role_pk','=','super-admin')
            ->exists();
        if( !$hasRole ){
            return redirect()->back();
        }
        return $next($request);
    }
}
