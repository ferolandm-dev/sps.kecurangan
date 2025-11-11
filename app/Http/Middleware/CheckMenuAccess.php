<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CheckMenuAccess
{
    public function handle(Request $request, Closure $next, $menuName)
    {
        if (auth()->check() && Gate::allows('access-menu', $menuName)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
