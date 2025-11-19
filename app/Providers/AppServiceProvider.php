<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if (str_contains(request()->getHost(), 'ngrok-free.dev')) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {

            /** USER MENU ACCESS */
            if (Auth::check()) {

                $access = DB::table('user_access')
                    ->where('user_id', Auth::id())
                    ->where('can_access', 1)
                    ->get();

                $userMenus = [];

                foreach ($access->groupBy('main_menu') as $main => $subs) {
                    $subList = $subs->pluck('sub_menu')->filter()->values()->toArray();
                    $userMenus[$main] = $subList ?: [null];
                }

                $view->with('userMenus', $userMenus);

            } else {
                $view->with('userMenus', []);
            }


            /** ===============================
             *  BREADCRUMB OTOMATIS
             * ===============================*/

            $segments = request()->segments();
            $routeName = \Route::currentRouteName();
            $params = request()->route()->parameters();
            $breadcrumb = '<nav aria-label="breadcrumb"><ol class="breadcrumb-sps">';


            /** FIX — Dashboard */
            if (request()->is('dashboard')) {
                $breadcrumb .= "<li class='active'>Dashboard</li></ol></nav>";
                return $view->with('breadcrumb', $breadcrumb);
            }


            /** MAP ROUTE → URL PREFIX */
            $prefixMap = [
                'user.index'   => 'users',
                'user.edit'    => 'users',

                'menus.index'  => 'menus',
                'menus.edit'   => 'menus',

                'salesman.data'    => 'salesman',
                'distributor.data' => 'distributor',

                'kecurangan.index' => 'kecurangan',
                'kecurangan.data'  => 'kecurangan',
                'sanksi.index'     => 'sanksi',
            ];


            /** AMBIL SEMUA MENU */
            $menus = DB::table('menus')->get();


            $firstSegment = $segments[0] ?? '';

            /** CARI MENU YANG COCOK DENGAN URL */
            $menu = $menus->first(function($m) use ($firstSegment, $prefixMap) {

                $target = $prefixMap[$m->route] ?? explode('.', $m->route)[0];

                return strtolower($firstSegment) === strtolower($target);
            });


            /** ===============================
             * 1) HALAMAN CREATE
             * ===============================*/
            if (in_array('create', $segments)) {

                if ($menu) {
                    if ($menu->main_menu) $breadcrumb .= "<li>{$menu->main_menu}</li>";
                    if ($menu->sub_menu)  $breadcrumb .= "<li><a href='" . route($menu->route) . "'>{$menu->sub_menu}</a></li>";
                }

                $breadcrumb .= "<li class='active'>Create</li>";
                return $view->with('breadcrumb', $breadcrumb."</ol></nav>");
            }


            /** ===============================
             * 2) HALAMAN EDIT (ADA ID)
             * ===============================*/
            if (!empty($params)) {

                $id = array_values($params)[0];

                if ($menu) {
                    if ($menu->main_menu) $breadcrumb .= "<li>{$menu->main_menu}</li>";
                    if ($menu->sub_menu)  $breadcrumb .= "<li><a href='" . route($menu->route) . "'>{$menu->sub_menu}</a></li>";
                }


                /** KHUSUS KECURANGAN → tampilkan ID_SALES NAMA_SALES */
                if ($firstSegment === 'kecurangan') {

                    $row = DB::table('kecurangan')->where('id', $id)->first();

                    if ($row) {
                        $breadcrumb .= "<li>{$row->id_sales} {$row->nama_sales}</li>";
                    }

                    if (in_array('edit', $segments)) {
                        $breadcrumb .= "<li class='active'>Edit</li>";
                    }

                    return $view->with('breadcrumb', $breadcrumb."</ol></nav>");
                }


                /** KHUSUS USERS */
                if ($firstSegment === 'users') {

                    $row = DB::table('users')->where('id', $id)->first();

                    if ($row) {
                        $breadcrumb .= "<li>{$row->name}</li>";
                    }

                    if (in_array('edit', $segments)) {
                        $breadcrumb .= "<li class='active'>Edit</li>";
                    }

                    return $view->with('breadcrumb', $breadcrumb."</ol></nav>");
                }


                /** DEFAULT MENU LAIN — HANYA TAMBAHKAN "Edit" */
                if (in_array('edit', $segments)) {
                    $breadcrumb .= "<li class='active'>Edit</li>";
                }

                return $view->with('breadcrumb', $breadcrumb."</ol></nav>");
            }


            /** ===============================
             * 3) HALAMAN INDEX
             * ===============================*/
            if ($menu) {

                if ($menu->main_menu) $breadcrumb .= "<li>{$menu->main_menu}</li>";
                if ($menu->sub_menu)  $breadcrumb .= "<li class='active'>{$menu->sub_menu}</li>";

                return $view->with('breadcrumb', $breadcrumb."</ol></nav>");
            }

        });
    }
}
