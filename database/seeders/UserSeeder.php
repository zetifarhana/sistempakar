<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = [
            [
                'username' => 'zeti',
                'level' => 'superadmin',
                'password' => Hash::make('12345678')
            ]
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
