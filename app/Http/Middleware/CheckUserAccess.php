<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckUserAccess
{
    /**
     * Middleware untuk memeriksa akses berdasarkan menu dan tipe izin (CRUD/Print)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $mainMenu
     * @param  string|null  $subMenu
     * @param  string  $type ('access', 'create', 'edit', 'delete', 'print')
     * @return mixed
     */
    public function handle($request, Closure $next, $mainMenu, $subMenu = null, $type = 'access')
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }

        // Ambil data akses user
        $query = DB::table('user_access')
            ->where('user_id', $user->id)
            ->where('main_menu', $mainMenu);

        // Jika submenu disertakan
        if ($subMenu) {
            $query->where(function ($q) use ($subMenu) {
                $q->where('sub_menu', $subMenu)
                  ->orWhereNull('sub_menu');
            });
        }

        $access = $query->first();

        if (!$access) {
            return abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Cek jenis izin berdasarkan parameter
        $allowed = match ($type) {
            'create' => $access->can_create ?? 0,
            'edit'   => $access->can_edit ?? 0,
            'delete' => $access->can_delete ?? 0,
            'print'  => $access->can_print ?? 0,
            default  => $access->can_access ?? 0,
        };
<<<<<<< HEAD

=======
        
        
>>>>>>> recovery-branch
        if (!$allowed) {
            return abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
