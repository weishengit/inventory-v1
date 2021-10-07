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
        if (User::count() <= 0) {
            User::firstOrCreate([
                'id' => 1,
                'role_id' => 1,
                'name' => 'lorenz',
                'email' => 'lorenz@example.com',
                'password' => Hash::make('123')
            ]);

            User::firstOrCreate([
                'id' => 2,
                'role_id' => 2,
                'name' => 'loraine',
                'email' => 'loraine@example.com',
                'password' => Hash::make('123')
            ]);
        }

        User::factory(10)->create();
    }
}
