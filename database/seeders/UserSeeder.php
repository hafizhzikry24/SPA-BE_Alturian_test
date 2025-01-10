<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Sasuke Uchiha',
            'email' => 'sasuke@example.com',
            'password' => Hash::make('sakuraharuno'),
        ]);
    }
}
