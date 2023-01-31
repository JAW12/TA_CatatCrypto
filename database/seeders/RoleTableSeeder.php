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
                'name' => 'user',
                'title' => 'User',
                'status' => 1,
                'permissions' => ['portfolio', 'portfolio-daftar', 'portfolio-tambah', 'portfolio-hapus', 'portfolio-ubah', 'portfolio-tambah-manual', 'journal', 'journal-daftar', 'journal-tambah', 'journal-hapus', 'journal-ubah', 'notes', 'notes-daftar', 'notes-tambah', 'notes-hapus', 'notes-ubah']
            ],
        ];

        foreach ($roles as $key => $value) {
            $permission = $value['permissions'];
            unset($value['permissions']);
            $role = Role::firstOrCreate($value);
            $role->givePermissionTo($permission);
        }
    }
}
