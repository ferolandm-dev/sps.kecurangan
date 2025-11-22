<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenusTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('menus')->truncate();

        DB::table('menus')->insert([

            // 1. DASHBOARD
            [
                'main_menu'  => 'Dashboard',
                'sub_menu'   => null,
                'icon'       => 'now-ui-icons business_chart-bar-32',
                'route'      => 'dashboard',
                'main_order' => 1,
                'order'      => 0,
                'can_crud'   => 0,
                'can_print'  => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 2. MASTER
            [
                'main_menu'  => 'Master',
                'sub_menu'   => 'Master Sanksi',
                'icon'       => 'now-ui-icons business_money-coins',
                'route'      => 'sanksi.index',
                'main_order' => 2,
                'order'      => 4,
                'can_crud'   => 1,
                'can_print'  => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'main_menu'  => 'Master',
                'sub_menu'   => 'Master Kecurangan',
                'icon'       => 'now-ui-icons sport_user-run',
                'route'      => 'kecurangan.index',
                'main_order' => 2,
                'order'      => 5,
                'can_crud'   => 1,
                'can_print'  => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 3. DATA
            [
                'main_menu'  => 'Data',
                'sub_menu'   => 'Data Distributor',
                'icon'       => 'now-ui-icons shopping_shop',
                'route'      => 'distributor.data',
                'main_order' => 3,
                'order'      => 1,
                'can_crud'   => 0,
                'can_print'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'main_menu'  => 'Data',
                'sub_menu'   => 'Data ASS',
                'icon'       => 'now-ui-icons business_badge',
                'route'      => 'ass.data',
                'main_order' => 3,
                'order'      => 2,
                'can_crud'   => 0,
                'can_print'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'main_menu'  => 'Data',
                'sub_menu'   => 'Data Salesman',
                'icon'       => 'now-ui-icons business_badge',
                'route'      => 'salesman.data',
                'main_order' => 3,
                'order'      => 3,
                'can_crud'   => 0,
                'can_print'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'main_menu'  => 'Data',
                'sub_menu'   => 'Data Kecurangan',
                'icon'       => 'now-ui-icons travel_info',
                'route'      => 'kecurangan.data',
                'main_order' => 3,
                'order'      => 4,
                'can_crud'   => 0,
                'can_print'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 4. PENGATURAN
            [
                'main_menu'  => 'Pengaturan',
                'sub_menu'   => 'User Management',
                'icon'       => 'now-ui-icons users_single-02',
                'route'      => 'user.index',
                'main_order' => 4,
                'order'      => 1,
                'can_crud'   => 1,
                'can_print'  => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'main_menu'  => 'Pengaturan',
                'sub_menu'   => 'Menu Management',
                'icon'       => 'now-ui-icons tech_laptop',
                'route'      => 'menus.index',
                'main_order' => 4,
                'order'      => 2,
                'can_crud'   => 1,
                'can_print'  => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
