<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin biblioteca',
            'email' => 'maria.aparicio@keyinstitute.edu.sv',
            'password' => 'key12345',
            'role'=> 'admin',
        ]);

        // User::factory(100)->create();

        // $this->call([
        //     BookSeeder::class,
        //     LoanSeeder::class
        // ]);
    }
}
