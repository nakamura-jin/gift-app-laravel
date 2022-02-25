<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TypeSeeder::class);
        \App\Models\User::factory(10)->create();
        \App\Models\Owner::factory(10)->create();
        \App\Models\Admin::factory(10)->create();
        \App\Models\Genre::factory(5)->create();
    }
}
