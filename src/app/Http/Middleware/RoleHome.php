<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleHome
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (Auth::check()) {
            switch (true) {
                case $user->isAdmin():
                    return redirect('/admin');
                    break;
                case $user->isTech():
                    return redirect('/tech');
                    break;
                case $user->isUser():
                    return redirect('/user');
                    break;
                default:
                    return redirect('/');
                    break;
            }
        }

        return $next($request);
    }
}
