<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        try {
            \App\Models\ClinicDetail::factory()->count(10)->create();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle case where no admin exists, log or output message
            echo "No admin user found, clinic not created.\n";
        }
    }
}
