<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistributorTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('distributors')->truncate();

        DB::table('distributors')->insert([
            ['id' => 'ABT01', 'distributor' => 'ABTI SERANG', 'status' => 'aktif', 'created_at' => '2025-11-05 08:17:45', 'updated_at' => '2025-11-13 02:51:16'],
            ['id' => 'ADS01', 'distributor' => 'ADHIR DARMA SENTOSA SUKABUMI', 'status' => 'aktif', 'created_at' => '2025-11-05 08:17:54', 'updated_at' => '2025-11-05 08:17:54'],
            ['id' => 'ADS02', 'distributor' => 'ADS CIANJUR', 'status' => 'aktif', 'created_at' => '2025-11-05 08:18:01', 'updated_at' => '2025-11-05 08:18:01'],
            ['id' => 'AJP01', 'distributor' => 'ANUGERAH JAYA PERKASA', 'status' => 'aktif', 'created_at' => '2025-11-05 08:18:09', 'updated_at' => '2025-11-05 08:18:09'],
            ['id' => 'ALA01', 'distributor' => 'ALAM ANUGERAH SARANA DAYA', 'status' => 'aktif', 'created_at' => '2025-11-05 08:18:16', 'updated_at' => '2025-11-13 15:53:31'],
            ['id' => 'ARZ01', 'distributor' => 'ARIZONA KARYA MITRA DENPASAR', 'status' => 'aktif', 'created_at' => '2025-11-05 08:18:28', 'updated_at' => '2025-11-05 08:18:28'],
            ['id' => 'ASR01', 'distributor' => 'ANUGERAH SINAR REJEKI', 'status' => 'aktif', 'created_at' => '2025-11-05 08:18:36', 'updated_at' => '2025-11-05 08:18:36'],
            ['id' => 'BJB01', 'distributor' => 'BINTANG JAYA DIPONEGORO', 'status' => 'aktif', 'created_at' => '2025-11-05 08:18:49', 'updated_at' => '2025-11-05 08:18:49'],
            ['id' => 'BPK01', 'distributor' => 'BOROBUDUR PRIMA SEJAHTERA KEDIRI', 'status' => 'aktif', 'created_at' => '2025-11-05 08:18:56', 'updated_at' => '2025-11-05 08:18:56'],
            ['id' => 'BPK02', 'distributor' => 'BOROBUDUR PRIMA SEJAHTERA TULUNGAGUNG', 'status' => 'aktif', 'created_at' => '2025-11-05 08:19:03', 'updated_at' => '2025-11-05 08:19:03'],
            ['id' => 'BPK03', 'distributor' => 'BOROBUDUR PRIMA SEJAHTERA BLITAR', 'status' => 'aktif', 'created_at' => '2025-11-05 08:19:13', 'updated_at' => '2025-11-06 08:56:09'],
            ['id' => 'BSJ01', 'distributor' => 'BERKAT SEJATI JAYA', 'status' => 'aktif', 'created_at' => '2025-11-05 08:19:23', 'updated_at' => '2025-11-05 08:19:23'],
            ['id' => 'CAS01', 'distributor' => 'CAHAYA ABADI SOLO', 'status' => 'aktif', 'created_at' => '2025-11-05 08:19:32', 'updated_at' => '2025-11-05 08:19:32'],
            ['id' => 'CBP01', 'distributor' => 'CAHAYA BARU PUTERA', 'status' => 'aktif', 'created_at' => '2025-11-05 08:19:39', 'updated_at' => '2025-11-05 08:19:39'],
            ['id' => 'CGS01', 'distributor' => 'CAHAYA GEMILAR SOLO', 'status' => 'aktif', 'created_at' => '2025-11-05 08:19:47', 'updated_at' => '2025-11-05 08:19:47'],
            ['id' => 'CGS02', 'distributor' => 'CGS SUKOHARJO', 'status' => 'aktif', 'created_at' => '2025-11-05 08:19:57', 'updated_at' => '2025-11-05 08:19:57'],
            ['id' => 'CGS03', 'distributor' => 'CGS SRAGEN', 'status' => 'aktif', 'created_at' => '2025-11-05 08:20:05', 'updated_at' => '2025-11-05 08:20:05'],
            ['id' => 'CGS04', 'distributor' => 'CGS WONOGIRI', 'status' => 'aktif', 'created_at' => '2025-11-05 08:20:17', 'updated_at' => '2025-11-05 08:20:17'],
            ['id' => 'CGS05', 'distributor' => 'CGS KLATEN', 'status' => 'aktif', 'created_at' => '2025-11-05 08:20:32', 'updated_at' => '2025-11-05 08:20:32'],
            ['id' => 'CSB01', 'distributor' => 'CIPTA SARANA BANGUN', 'status' => 'aktif', 'created_at' => '2025-11-05 08:20:41', 'updated_at' => '2025-11-05 08:20:41'],
            ['id' => 'CSU01', 'distributor' => 'CAHAYA SETIA UTAMA', 'status' => 'aktif', 'created_at' => '2025-11-05 08:20:51', 'updated_at' => '2025-11-05 08:20:51'],
            ['id' => 'DCN01', 'distributor' => 'DCN DISTRIBUTOR', 'status' => 'aktif', 'created_at' => '2025-11-10 06:09:47', 'updated_at' => '2025-11-10 06:09:47'],
            ['id' => 'GPP01', 'distributor' => 'GADING PURI PERKASA SIDOARJO', 'status' => 'aktif', 'created_at' => '2025-11-05 08:20:58', 'updated_at' => '2025-11-05 08:20:58'],
            ['id' => 'GPS01', 'distributor' => 'GADING PURI PERKASA SURABAYA', 'status' => 'aktif', 'created_at' => '2025-11-05 08:21:12', 'updated_at' => '2025-11-05 08:21:12'],
            ['id' => 'GTA01', 'distributor' => 'GRASIA TIMOR ABADI', 'status' => 'aktif', 'created_at' => '2025-11-05 08:21:19', 'updated_at' => '2025-11-05 08:21:19'],
            ['id' => 'HRY01', 'distributor' => 'HARLY', 'status' => 'aktif', 'created_at' => '2025-11-05 08:21:26', 'updated_at' => '2025-11-06 10:03:14'],
            ['id' => 'JMU01', 'distributor' => 'JAYA MURNI PADANG', 'status' => 'aktif', 'created_at' => '2025-11-05 08:21:35', 'updated_at' => '2025-11-05 08:21:35'],
            ['id' => 'JSB01', 'distributor' => 'JAYA SUBUR BANYUWANGI', 'status' => 'aktif', 'created_at' => '2025-11-05 08:21:42', 'updated_at' => '2025-11-05 08:21:42'],
            ['id' => 'JSU01', 'distributor' => 'JAYA SUBUR JEMBER', 'status' => 'aktif', 'created_at' => '2025-11-05 08:21:56', 'updated_at' => '2025-11-05 08:21:56'],
            ['id' => 'KEM01', 'distributor' => 'KARANG EMPAT', 'status' => 'aktif', 'created_at' => '2025-11-05 08:22:08', 'updated_at' => '2025-11-05 08:22:08'],
            ['id' => 'LJS01', 'distributor' => 'LANGGENG JAYA SENTOSA', 'status' => 'aktif', 'created_at' => '2025-11-05 08:22:15', 'updated_at' => '2025-11-05 08:22:15'],
            ['id' => 'MGI01', 'distributor' => 'MGI TEGAL', 'status' => 'aktif', 'created_at' => '2025-11-05 08:22:26', 'updated_at' => '2025-11-05 08:22:26'],
            ['id' => 'MGJ01', 'distributor' => 'MGI PURWOKERTO', 'status' => 'aktif', 'created_at' => '2025-11-05 08:22:33', 'updated_at' => '2025-11-05 08:22:33'],
            ['id' => 'MGK01', 'distributor' => 'MULTI JAYA SENTOSA', 'status' => 'aktif', 'created_at' => '2025-11-05 08:22:43', 'updated_at' => '2025-11-05 08:22:43'],
            ['id' => 'MMU01', 'distributor' => 'MAKMUR MANDIRI UTAMA', 'status' => 'aktif', 'created_at' => '2025-11-05 08:22:52', 'updated_at' => '2025-11-05 08:22:52'],
            ['id' => 'PJN01', 'distributor' => 'PANJUNAN PERKASA JAYA BANDUNG', 'status' => 'aktif', 'created_at' => '2025-11-05 08:23:05', 'updated_at' => '2025-11-05 08:23:05'],
            ['id' => 'PRB01', 'distributor' => 'PRABU DISTRINDO PALEMBANG', 'status' => 'aktif', 'created_at' => '2025-11-05 08:23:11', 'updated_at' => '2025-11-05 08:23:11'],
            ['id' => 'RMH01', 'distributor' => 'RAMAH', 'status' => 'aktif', 'created_at' => '2025-11-05 08:23:18', 'updated_at' => '2025-11-05 08:23:18'],
            ['id' => 'SJB01', 'distributor' => 'SARWA JAYA BERSAMA', 'status' => 'aktif', 'created_at' => '2025-11-05 08:23:26', 'updated_at' => '2025-11-05 08:23:26'],
            ['id' => 'SJT01', 'distributor' => 'SYAREKAH JAYA', 'status' => 'aktif', 'created_at' => '2025-11-05 08:23:35', 'updated_at' => '2025-11-05 08:23:35'],
            ['id' => 'SMB01', 'distributor' => 'SYAREKAH MAKMUR', 'status' => 'aktif', 'created_at' => '2025-11-05 08:23:46', 'updated_at' => '2025-11-05 08:23:46'],
            ['id' => 'SMP01', 'distributor' => 'SEMAR PERDANA BEKASI', 'status' => 'aktif', 'created_at' => '2025-11-05 08:23:54', 'updated_at' => '2025-11-05 08:23:54'],
            ['id' => 'SMS01', 'distributor' => 'SUMBER MAKMUR SENTOSA', 'status' => 'aktif', 'created_at' => '2025-11-05 08:24:01', 'updated_at' => '2025-11-05 08:24:01'],
            ['id' => 'SSB01', 'distributor' => 'SUMBER SEHAT BERSAMA', 'status' => 'aktif', 'created_at' => '2025-11-05 08:24:09', 'updated_at' => '2025-11-05 08:24:09'],
            ['id' => 'SSC01', 'distributor' => 'SINAR SURYA CEMERLANG', 'status' => 'aktif', 'created_at' => '2025-11-05 08:24:22', 'updated_at' => '2025-11-05 08:24:22'],
            ['id' => 'SSM01', 'distributor' => 'SUMBER SEHAT MAKMUR', 'status' => 'aktif', 'created_at' => '2025-11-05 08:24:28', 'updated_at' => '2025-11-05 08:24:28'],
            ['id' => 'STA01', 'distributor' => 'SINTER PASURUAN', 'status' => 'aktif', 'created_at' => '2025-11-05 08:24:35', 'updated_at' => '2025-11-05 08:24:35'],
            ['id' => 'STB01', 'distributor' => 'SINTER PROBOLINGGO', 'status' => 'aktif', 'created_at' => '2025-11-05 08:25:03', 'updated_at' => '2025-11-05 08:25:03'],
            ['id' => 'SUN01', 'distributor' => 'SINAR USAHA NIAGA', 'status' => 'aktif', 'created_at' => '2025-11-05 08:25:09', 'updated_at' => '2025-11-05 08:25:09'],
            ['id' => 'TBP01', 'distributor' => 'TOTALINDO BANGGAI PERKASA', 'status' => 'aktif', 'created_at' => '2025-11-05 08:25:14', 'updated_at' => '2025-11-05 08:25:14'],
            ['id' => 'TIP01', 'distributor' => 'TIP', 'status' => 'aktif', 'created_at' => '2025-11-05 08:25:28', 'updated_at' => '2025-11-05 08:25:28'],
            ['id' => 'TJL01', 'distributor' => 'TAMARIN JAYA LOBAR', 'status' => 'aktif', 'created_at' => '2025-11-05 08:25:35', 'updated_at' => '2025-11-05 08:25:35'],
            ['id' => 'TJL02', 'distributor' => 'TAMARIN JAYA LOTIM', 'status' => 'aktif', 'created_at' => '2025-11-05 08:25:41', 'updated_at' => '2025-11-05 08:25:41'],
            ['id' => 'TJM01', 'distributor' => 'TARGET JAYA MANADO', 'status' => 'aktif', 'created_at' => '2025-11-05 08:25:47', 'updated_at' => '2025-11-05 08:25:47'],
            ['id' => 'TRP01', 'distributor' => 'TIRTA RAHARJA PONOROGO', 'status' => 'aktif', 'created_at' => '2025-11-05 08:25:52', 'updated_at' => '2025-11-05 08:25:52'],
            ['id' => 'TRQ01', 'distributor' => 'TIRTA RAHARJA PACITAN', 'status' => 'aktif', 'created_at' => '2025-11-05 08:26:00', 'updated_at' => '2025-11-05 08:26:00'],
            ['id' => 'TSP01', 'distributor' => 'TSM PURWAKARTA', 'status' => 'aktif', 'created_at' => '2025-11-05 08:26:12', 'updated_at' => '2025-11-05 08:26:12'],
            ['id' => 'TSS01', 'distributor' => 'TRIYANTO SUKSES MANDIRI SUBANG', 'status' => 'aktif', 'created_at' => '2025-11-05 08:26:19', 'updated_at' => '2025-11-05 08:26:19'],
        ]);
    }
}