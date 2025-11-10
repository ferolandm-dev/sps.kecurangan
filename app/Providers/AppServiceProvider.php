<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    View::composer('*', function ($view) {
        if (Auth::check()) {
            $access = DB::table('user_access')
                ->where('user_id', Auth::id())
                ->where('can_access', 1)
                ->get();

            $userMenus = [];

            foreach ($access->groupBy('main_menu') as $main => $subs) {
                // Ambil daftar submenu (bisa kosong/null)
                $subList = $subs->pluck('sub_menu')->filter()->values()->toArray();

                // Jika tidak ada submenu, masukkan null agar main_menu tetap terdaftar
                $userMenus[$main] = $subList ?: [null];
            }

            $view->with('userMenus', $userMenus);
        } else {
            $view->with('userMenus', []);
        }
    });
}

}
