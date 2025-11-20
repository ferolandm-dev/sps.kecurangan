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
            // Dashboard
            [
                'main_menu'  => 'Dashboard',
                'sub_menu'   => null,
                'icon'       => 'now-ui-icons business_chart-pie-36',
                'route'      => 'dashboard',
                'main_order' => 1,
                'order'      => 0,
                'can_crud'   => 0,
                'can_print'  => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

    
            [
                'main_menu'  => 'Master',
                'sub_menu'   => 'Master Sanksi',
                'icon'       => null,
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
                'icon'       => 'now-ui-icons design_bullet-list-67',
                'route'      => 'kecurangan.index',
                'main_order' => 2,
                'order'      => 5,
                'can_crud'   => 1,
                'can_print'  => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // DATA
            [
                'main_menu'  => 'Data',
                'sub_menu'   => 'Data Distributor',
                'icon'       => 'now-ui-icons design_bullet-list-67',
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
                'sub_menu'   => 'Data Salesman',
                'icon'       => 'now-ui-icons design_bullet-list-67',
                'route'      => 'salesman.data',
                'main_order' => 3,
                'order'      => 2,
                'can_crud'   => 0,
                'can_print'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            [
                'main_menu'  => 'Data',
                'sub_menu'   => 'Data Kecurangan',
                'icon'       => 'now-ui-icons design_bullet-list-67',
                'route'      => 'kecurangan.data',
                'main_order' => 3,
                'order'      => 4,
                'can_crud'   => 0,
                'can_print'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // PENGATURAN
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