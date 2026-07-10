<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Classe;

class ClasseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Classe::create([
            'nomClasse'=>'L3 Génie Logiciel',
            'niveau'=>'Licence 3',
            'anneeAcademique'=>'2025/2026'
        ]);
        Classe::create([
            'nomClasse'=>'L2 Génie Logiciel',
            'niveau'=>'Licence 2',
            'anneeAcademique'=>'2025/2026'
        ]);
        Classe::create([
            'nomClasse'=>'L1 Génie Logiciel',
            'niveau'=>'Licence 1',
            'anneeAcademique'=>'2025/2026'
        ]);
    }
}
