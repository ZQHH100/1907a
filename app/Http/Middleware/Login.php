<?php

namespace App\Http\Middleware;

use Closure;

class Login
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       $user=session('user');
        //$user_id=Auth::id();
       // dd($user);
        if(!$user){
            return redirect('/login');
        }
        return $next($request);
    }
}
