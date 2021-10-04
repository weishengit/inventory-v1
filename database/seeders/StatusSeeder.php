<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create(['status' => 'New']);
        Status::create(['status' => 'Approved']);
        Status::create(['status' => 'Pending']);
        Status::create(['status' => 'Closed']);
        Status::create(['status' => 'Void']);
    }
}
