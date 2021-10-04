<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'role_id' => 1,
            'name' => 'lorenz',
            'email' => 'lorenz@example.com',
            'password' => Hash::make('123')
        ]);

        User::create([
            'role_id' => 2,
            'name' => 'loraine',
            'email' => 'loraine@example.com',
            'password' => Hash::make('123')
        ]);
    }
}
