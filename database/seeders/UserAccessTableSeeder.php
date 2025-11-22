<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserAccessTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user_access')->truncate();

        DB::table('user_access')->insert([

            // Dashboard
            [
                'user_id'     => 1,
                'main_menu'   => 'Dashboard',
                'sub_menu'    => null,
                'can_access'  => 1,
                'can_create'  => 0,
                'can_edit'    => 0,
                'can_delete'  => 0,
                'can_print'   => 0,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // Master Sanksi
            [
                'user_id'     => 1,
                'main_menu'   => 'Master',
                'sub_menu'    => 'Master Sanksi',
                'can_access'  => 1,
                'can_create'  => 1,
                'can_edit'    => 1,
                'can_delete'  => 1,
                'can_print'   => 0,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // Master Kecurangan
            [
                'user_id'     => 1,
                'main_menu'   => 'Master',
                'sub_menu'    => 'Master Kecurangan',
                'can_access'  => 1,
                'can_create'  => 1,
                'can_edit'    => 1,
                'can_delete'  => 1,
                'can_print'   => 0,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // Data Distributor
            [
                'user_id'     => 1,
                'main_menu'   => 'Data',
                'sub_menu'    => 'Data Distributor',
                'can_access'  => 1,
                'can_create'  => 0,
                'can_edit'    => 0,
                'can_delete'  => 0,
                'can_print'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // Data ASS
            [
                'user_id'     => 1,
                'main_menu'   => 'Data',
                'sub_menu'    => 'Data ASS',
                'can_access'  => 1,
                'can_create'  => 0,
                'can_edit'    => 0,
                'can_delete'  => 0,
                'can_print'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // Data Salesman
            [
                'user_id'     => 1,
                'main_menu'   => 'Data',
                'sub_menu'    => 'Data Salesman',
                'can_access'  => 1,
                'can_create'  => 0,
                'can_edit'    => 0,
                'can_delete'  => 0,
                'can_print'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // Data Kecurangan
            [
                'user_id'     => 1,
                'main_menu'   => 'Data',
                'sub_menu'    => 'Data Kecurangan',
                'can_access'  => 1,
                'can_create'  => 0,
                'can_edit'    => 0,
                'can_delete'  => 0,
                'can_print'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // Pengaturan → User Management
            [
                'user_id'     => 1,
                'main_menu'   => 'Pengaturan',
                'sub_menu'    => 'User Management',
                'can_access'  => 1,
                'can_create'  => 1,
                'can_edit'    => 1,
                'can_delete'  => 1,
                'can_print'   => 0,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // Pengaturan → Menu Management
            [
                'user_id'     => 1,
                'main_menu'   => 'Pengaturan',
                'sub_menu'    => 'Menu Management',
                'can_access'  => 1,
                'can_create'  => 1,
                'can_edit'    => 1,
                'can_delete'  => 1,
                'can_print'   => 0,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
