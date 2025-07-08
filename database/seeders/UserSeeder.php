<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRoleId = DB::table('roles')->where('name', 'Admin')->value('id');
        $hrRoleId = DB::table('roles')->where('name', 'HR')->value('id');

        DB::table('users')->updateOrInsert(
            ['email' => 'admin123@gmail.com'],
            ['name' => 'Admin', 'password' => Hash::make('admin123'), 'role_id' => $adminRoleId]
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'hr123@gmail.com'],
            ['name' => 'HR', 'password' => Hash::make('hr123'), 'role_id' => $hrRoleId]
        );
    }
}
