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
        $users = [
            [
                'name' => 'Ralph Quidet',
                'email' => 'ralph@email.com',
                'password' => Hash::make('Test123!'),
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
