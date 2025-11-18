<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Matikan FK
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Opsional: truncate tabel yang akan diisi
        DB::table('users')->truncate();
        DB::table('distributors')->truncate(); // jika ada
        DB::table('menus')->truncate();
        DB::table('user_access')->truncate();

        // Jalankan semua seeder
        $this->call([
            UsersTableSeeder::class,
            DistributorTableSeeder::class,
            MenusTableSeeder::class,
            UserAccessTableSeeder::class,
        ]);

        // Nyalakan kembali FK
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}