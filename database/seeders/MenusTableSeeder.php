<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel dulu agar tidak duplikat
        DB::table('menus')->truncate();

        DB::table('menus')->insert([
            [
                'main_menu'  => 'Master',
                'sub_menu'   => 'Master Distributor',
                'icon'       => 'now-ui-icons design_bullet-list-67',
                'route'      => 'distributors.index',
                'order'      => 1,
                'can_crud'   => 1,
                'can_print'  => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'main_menu'  => 'Master',
                'sub_menu'   => 'Master Sales',
                'icon'       => 'now-ui-icons design_bullet-list-67',
                'route'      => 'sales.index',
                'order'      => 2,
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
                'order'      => 3,
                'can_crud'   => 0,
                'can_print'  => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'main_menu'  => 'Data',
                'sub_menu'   => 'Data Distributor',
                'icon'       => 'now-ui-icons design_bullet-list-67',
                'route'      => 'distributors.data',
                'order'      => 1,
                'can_crud'   => 0,
                'can_print'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'main_menu'  => 'Data',
                'sub_menu'   => 'Data Sales',
                'icon'       => 'now-ui-icons design_bullet-list-67',
                'route'      => 'sales.data',
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
                'order'      => 3,
                'can_crud'   => 0,
                'can_print'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'main_menu'  => 'Pengaturan',
                'sub_menu'   => 'User Management',
                'icon'       => 'now-ui-icons users_single-02',
                'route'      => 'user.index',
                'order'      => 2,
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
                'order'      => 3,
                'can_crud'   => 1,
                'can_print'  => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'main_menu'  => null,
                'sub_menu'   => 'Dashboard',
                'icon'       => 'now-ui-icons business_chart-pie-36',
                'route'      => 'home',
                'order'      => 0,
                'can_crud'   => 0,
                'can_print'  => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}