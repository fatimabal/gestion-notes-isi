<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nom'=>'Diop',
            'prenom'=>'Makhou',
            'email'=>'etudiant@test.com',
            'password'=>Hash::make('123456'),
            'role'=>'etudiant',
            'telephone'=>'779081356',
        ]);
        User::create([
            'nom'=>'Diop',
            'prenom'=>'Elhadji Mar',
            'email'=>'enseignant@test.com',
            'password'=>Hash::make('123456'),
            'role'=>'enseignant',
            'telephone'=>'770099009',
        ]);
        User::create([
            'nom'=>'Thiam',
            'prenom'=>'Anta',
            'email'=>'scolarite@test.com',
            'password'=>Hash::make('123456'),
            'role'=>'scolarite',
            'telephone'=>'767891221',
        ]);
        User::create([
            'nom'=>'Bal',
            'prenom'=>'Mohamed',
            'email'=>'parent@test.com',
            'password'=>Hash::make('123456'),
            'role'=>'parent',
            'telephone'=>'781023456',
        ]);
    }
}
