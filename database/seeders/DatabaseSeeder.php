<?php

namespace Database\Seeders;

use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
            //Buat Admin
            User::create([
                'name' => 'Admin',
                'email' => 'admin@sekolah.com',
                'password' => Hash::make('password'),
            ]);

            //Buat Departemen
            $it = Departemen::create(['nama' => 'Teknologi Informasi', 'kode' => 'TI']);
            $hrd = Departemen::create(['nama' => 'Sumber Daya Manusia', 'kode' => 'HRD']);
            $fin = Departemen::create(['nama' => 'Keuangan', 'kode' => 'FIN']);
            $ops = Departemen::create(['nama' => 'Operasional', 'kode' => 'OPS']);

            //Buat Jabatan
            $programmer = Jabatan::create(['departemen_id' => $it->id, 'nama' => 'Programmer', 'gaji_pokok' => 10000000]);
            $analyst = Jabatan::create(['departemen_id' => $it->id, 'nama' => 'System Analyst', 'gaji_pokok' => 12000000]);
            $hrdStaff = Jabatan::create(['departemen_id' => $hrd->id, 'nama' => 'Staff HRD', 'gaji_pokok' => 8000000]);
            $hrdMgr = Jabatan::create(['departemen_id' => $hrd->id, 'nama' => 'Manager HRD', 'gaji_pokok' => 15000000]);
            $akuntan = Jabatan::create(['departemen_id' => $fin->id, 'nama' => 'Akuntan', 'gaji_pokok' => 9000000]);
            $kasir = Jabatan::create(['departemen_id' => $fin->id, 'nama' => 'Kasir', 'gaji_pokok' => 9000000]);
            $sopir = Jabatan::create(['departemen_id' => $fin->id, 'nama' => 'Sopir', 'gaji_pokok' => 9000000]);
            $gudang = Jabatan::create(['departemen_id' => $fin->id, 'nama' => 'Staff Gudang', 'gaji_pokok' => 9000000]);

            //Buat Karyawan
            $karyawan = [
                ['nik' => 'KRY-001', 'nama' => 'Budi Santoso', 'email' => 'budi@sekolah.com', 'dept' => $it, 'jabatan_id' => $programmer->id, 'gaji' =>100_000_000_000, 'tunj' => 500_000_000],
                ['nik' => 'KRY-002', 'nama' => 'Siti Aminah', 'email' => 'siti@sekolah.com', 'dept' => $it, 'jabatan_id' => $analyst->id, 'gaji' => 80_000_000_000, 'tunj' => 400_000_000],
                ['nik' => 'KRY-003', 'nama' => 'Joko Widodo', 'email' => 'joko@sekolah.com', 'dept' => $hrd, 'jabatan_id' => $hrdStaff->id, 'gaji' => 70_000_000_000, 'tunj' => 350_000_000],
                ['nik' => 'KRY-004', 'nama' => 'Dewi Sartika', 'email' => 'dewi@sekolah.com', 'dept' => $hrd, 'jabatan_id' => $hrdMgr->id, 'gaji' => 90_000_000_000, 'tunj' => 450_000_000],
                ['nik' => 'KRY-005', 'nama' => 'Andi Setiawan', 'email' => 'andi@sekolah.com', 'dept' => $fin, 'jabatan_id' => $akuntan->id, 'gaji' => 60_000_000_000, 'tunj' => 300_000_000],
                ['nik' => 'KRY-006', 'nama' => 'Rina Wulandari', 'email' => 'rina@sekolah.com', 'dept' => $fin, 'jabatan_id' => $kasir->id, 'gaji' => 50_000_000_000, 'tunj' => 250_000_000],
                ['nik' => 'KRY-007', 'nama' => 'Budi Santoso', 'email' => 'budi@sekolah.com', 'dept' => $ops, 'jabatan_id' => $sopir->id, 'gaji' => 40_000_000_000, 'tunj' => 200_000_000],
                ['nik' => 'KRY-008', 'nama' => 'Siti Aminah', 'email' => 'siti@sekolah.com', 'dept' => $ops, 'jabatan_id' => $gudang->id, 'gaji' => 30_000_000_000, 'tunj' => 150_000_000],
            ];

            foreach ($karyawan as $data) {
                Karyawan::create([
                    'nik' => $data['nik'],
                    'nama' => $data['nama'],
                    'email' => $data['email'],
                    'departemen_id' => $data['dept']->id,
                    'jabatan_id' => $data['jabatan_id'],
                    'gaji_pokok' => $data['gaji'],
                    'tunjangan' => $data['tunj'],
                    'tanggal_masuk' => now()->subYears(rand(1, 10))->format('Y-m-d'),
                    'jenis_kelamin' => rand(0, 1) ? 'L' : 'P',
                    'telepon' => '08' . rand(100_000_000, 999_999_999),
                    'status' => 'aktif',
                    'bank' => 'Bank ABC',
                    'no_rekening' => '1234567890',
                ]);
            }
    }
}
