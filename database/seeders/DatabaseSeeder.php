<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Automatically create the admin user if it doesn't exist
        $adminEmail = 'admin@admin.com';
        if (!User::where('email', $adminEmail)->exists()) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => $adminEmail,
                'password' => bcrypt('unique@123'),
            ]);
        }

        $this->call(StateSeeder::class);
        $this->call(CompanyDetailSeeder::class);
    }
}
