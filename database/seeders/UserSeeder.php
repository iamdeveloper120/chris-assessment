<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $users = array();
        $users[] = [
            'first_name' => 'Muhammad',
            'last_name' => 'Farhan',
            'email' => 'admin@darvis.com',
            'username' => 'm.farhan'
        ];
        $users[] = [
            'first_name' => 'chris',
            'last_name' => 'as',
            'email' => 'chris.as@darvis.com',
            'username' => 'chris.as'
        ];
        $passwordSuffix = '2022';
        foreach ($users as $user) {
            $secret = bcrypt(Str::lower($user['first_name'] . $passwordSuffix));
            $userRecord = [
                'first_name' =>  Str::ucfirst($user['first_name']),
                'last_name' =>  Str::ucfirst($user['last_name']),
                'email' => Str::lower($user['email']),
                'password' => $secret,
            ];
            User::updateOrCreate([
                'email' => $user['email']
            ], $userRecord);
        }
    }
}
