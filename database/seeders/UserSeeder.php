<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Company;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::firstOrCreate([
            'name' => 'Test Company',
            'email' => 'info@testcompany.com',
        ]);

        $ralph = User::firstOrCreate([
            'name' => 'Ralph',
            'email' => 'ralph@email.com',
            'password' => Hash::make('Test123!'),
        ]);

        $test = User::firstOrCreate([
            'name' => 'Test',
            'email' => 'test@email.com',
            'password' => Hash::make('Test123!'),
        ]);

        $ralph->companies()->attach($company->id, ['role' => 'owner']);
        $test->companies()->attach($company->id, ['role' => 'employee']);
    }
}
