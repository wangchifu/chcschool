<?php
namespace App\Http\Middleware;
use Closure;
class DnsAdminMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (session('dns_admin') == 1) {
            return $next($request);
        }else{
            return redirect()->route('index');
        }
    }
}
