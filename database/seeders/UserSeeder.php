<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserInformation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $newUser = User::create([
                'name'     => fake()->name(),
                'email'    => 'demouser' . $i . '@gmail.com',
                'password' => Hash::make('password123')
            ]);

            UserInformation::create([
                'user_id'        => $newUser->id,
                'bio'            => fake()->sentence(12),
                'address'        => fake()->address(),
                'contact_number' => fake()->phoneNumber(),
                'gender'         => fake()->randomElement(['M', 'F']),
                'birth_date'     => fake()->dateTimeBetween('-40 years', '-18 years')
            ]);
        }
    }
}
