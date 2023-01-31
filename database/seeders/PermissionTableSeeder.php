<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name' => 'portfolio',
                'title' => 'Portofolio',
            ],
            [
                'name' => 'portfolio-daftar',
                'title' => 'Daftar Portofolio',
                'parent_id' => 1,
            ],
            [
                'name' => 'portfolio-tambah',
                'title' => 'Tambah Portofolio',
                'parent_id' => 1,
            ],
            [
                'name' => 'portfolio-hapus',
                'title' => 'Hapus Portofolio',
                'parent_id' => 1,
            ],
            [
                'name' => 'portfolio-ubah',
                'title' => 'Ubah Portofolio',
                'parent_id' => 1,
            ],
            [
                'name' => 'portfolio-tambah-manual',
                'title' => 'Tambah Portofolio Manual',
                'parent_id' => 3,
            ],
            [
                'name' => 'portfolio-tambah-binance',
                'title' => 'Tambah Portofolio Binance',
                'parent_id' => 3,
            ],
            [
                'name' => 'assets-transactions-notifikasi',
                'title' => 'Notifikasi Transaksi Aset',
                'parent_id' => 1,
            ],
            [
                'name' => 'journal',
                'title' => 'Jurnal',
            ],
            [
                'name' => 'journal-daftar',
                'title' => 'Daftar Jurnal',
                'parent_id' => 9,
            ],
            [
                'name' => 'journal-tambah',
                'title' => 'Tambah Jurnal',
                'parent_id' => 9,
            ],
            [
                'name' => 'journal-hapus',
                'title' => 'Hapus Jurnal',
                'parent_id' => 9,
            ],
            [
                'name' => 'journal-ubah',
                'title' => 'Ubah Jurnal',
                'parent_id' => 9,
            ],
            [
                'name' => 'notes',
                'title' => 'Catatan',
                'parent_id' => 9,
            ],
            [
                'name' => 'notes-daftar',
                'title' => 'Daftar Catatan',
                'parent_id' => 14,
            ],
            [
                'name' => 'notes-tambah',
                'title' => 'Tambah Catatan',
                'parent_id' => 14,
            ],
            [
                'name' => 'notes-hapus',
                'title' => 'Hapus Catatan',
                'parent_id' => 14,
            ],
            [
                'name' => 'notes-ubah',
                'title' => 'Ubah Catatan',
                'parent_id' => 14,
            ],
        ];

        foreach ($permissions as $value) {
            Permission::firstOrCreate($value);
        }
    }
}
