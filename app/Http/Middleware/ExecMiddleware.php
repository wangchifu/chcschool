<?php
namespace App\Http\Middleware;

use App\UserGroup;
use Closure;
use Illuminate\Support\Facades\Auth;
class ExecMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $check = UserGroup::where('user_id',auth()->user()->id)
            ->where('group_id',1)
            ->first();

        if (Auth::guard($guard)->check() && !empty($check)) {
            return $next($request);
        }else{
            return redirect('/');
        }
    }
}
