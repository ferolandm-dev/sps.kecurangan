<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserAccessTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel dulu biar tidak duplikat
        DB::table('user_access')->truncate();

        // Masukkan data akses default admin
        DB::table('user_access')->insert([
            [
                'user_id'     => 1,
                'main_menu'   => '',
                'sub_menu'    => 'Dashboard',
                'can_access'  => 1,
                'can_create'  => 0,
                'can_edit'    => 0,
                'can_delete'  => 0,
                'can_print'   => 0,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
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
            [
                'user_id'     => 1,
                'main_menu'   => 'Data',
                'sub_menu'    => 'Data Sales',
                'can_access'  => 1,
                'can_create'  => 0,
                'can_edit'    => 0,
                'can_delete'  => 0,
                'can_print'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
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
            [
                'user_id'     => 1,
                'main_menu'   => 'Master',
                'sub_menu'    => 'Master Distributor',
                'can_access'  => 1,
                'can_create'  => 1,
                'can_edit'    => 1,
                'can_delete'  => 1,
                'can_print'   => 0,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'user_id'     => 1,
                'main_menu'   => 'Master',
                'sub_menu'    => 'Master Sales',
                'can_access'  => 1,
                'can_create'  => 1,
                'can_edit'    => 1,
                'can_delete'  => 1,
                'can_print'   => 0,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'user_id'     => 1,
                'main_menu'   => 'Master',
                'sub_menu'    => 'Master Kecurangan',
                'can_access'  => 1,
                'can_create'  => 0,
                'can_edit'    => 0,
                'can_delete'  => 0,
                'can_print'   => 0,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
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