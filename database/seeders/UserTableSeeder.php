<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
        /**
         * Run the database seeds.
     */
    public function run()
    {
         DB::table('users')->delete(); 

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ],
        [
            'name' => 'Fauzan',
            'email' => 'fauzan@gmail.com',
            'password' => bcrypt('fauzan123'),
            'role' => 'petugas',
        ]);

    }
}
