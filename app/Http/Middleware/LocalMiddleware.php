<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class LocalMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check() && Auth::user()->login_type == "local") {
            return $next($request);
        }else{
            return redirect('/');
        }
    }
}
