<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('customers')->updateOrInsert(
            ['email' => 'customer123@gmail.com'],
            ['name' => 'Customer', 'password' => Hash::make('customer123')]
        );
    }
}
