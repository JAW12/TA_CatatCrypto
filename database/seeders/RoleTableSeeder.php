<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'admin',
                'title' => 'Admin',
                'status' => 1,
                'permissions' => []
            ],
            [
                'name' => 'trial',
                'title' => 'Trial',
                'status' => 1,
                'permissions' => ['portfolio', 'portfolio-daftar', 'portfolio-tambah', 'portfolio-hapus', 'portfolio-ubah', 'portfolio-tambah-manual', 'journal', 'journal-daftar', 'journal-tambah', 'journal-hapus', 'journal-ubah', 'notes', 'notes-daftar', 'notes-tambah', 'notes-hapus', 'notes-ubah']
            ],
            [
                'name' => 'free',
                'title' => 'Free',
                'status' => 1,
                'permissions' => ['portfolio', 'portfolio-daftar', 'journal', 'journal-daftar', 'notes', 'notes-daftar']
            ],
            [
                'name' => 'basic',
                'title' => 'Basic',
                'status' => 1,
                'permissions' => ['portfolio', 'portfolio-daftar', 'portfolio-tambah', 'portfolio-hapus', 'portfolio-ubah', 'portfolio-tambah-manual', 'journal', 'journal-daftar', 'journal-tambah', 'journal-hapus', 'journal-ubah', 'notes', 'notes-daftar', 'notes-tambah', 'notes-hapus', 'notes-ubah']
            ],
            [
                'name' => 'home',
                'title' => 'Home',
                'status' => 1,
                'permissions' => ['portfolio', 'portfolio-daftar', 'portfolio-tambah', 'portfolio-hapus', 'portfolio-ubah', 'portfolio-tambah-manual', 'portfolio-tambah-binance', 'journal', 'journal-daftar', 'journal-tambah', 'journal-hapus', 'journal-ubah', 'notes', 'notes-daftar', 'notes-tambah', 'notes-hapus', 'notes-ubah']
            ],
            [
                'name' => 'professional',
                'title' => 'Professional',
                'status' => 1,
                'permissions' => ['portfolio', 'portfolio-daftar', 'portfolio-tambah', 'portfolio-hapus', 'portfolio-ubah', 'portfolio-tambah-manual', 'portfolio-tambah-binance', 'assets-transactions-notifikasi', 'journal', 'journal-daftar', 'journal-tambah', 'journal-hapus', 'journal-ubah', 'notes', 'notes-daftar', 'notes-tambah', 'notes-hapus', 'notes-ubah']
            ],
            [
                'name' => 'business',
                'title' => 'Business',
                'status' => 1,
                'permissions' => ['portfolio', 'portfolio-daftar', 'portfolio-tambah', 'portfolio-hapus', 'portfolio-ubah', 'portfolio-tambah-manual', 'portfolio-tambah-binance', 'assets-transactions-notifikasi', 'journal', 'journal-daftar', 'journal-tambah', 'journal-hapus', 'journal-ubah', 'notes', 'notes-daftar', 'notes-tambah', 'notes-hapus', 'notes-ubah']
            ],
        ];

        foreach ($roles as $key => $value) {
            $permission = $value['permissions'];
            unset($value['permissions']);
            $role = Role::create($value);
            $role->givePermissionTo($permission);
        }
    }
}
