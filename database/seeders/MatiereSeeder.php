<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Matiere;

class MatiereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Matiere::create([
            'libelle'=>'Algorithmique',
            'credits'=>3,
            'volumeHoraire'=>30,
            'coefficient'=>2,
            'classe_id'=>1
        ]);
        Matiere::create([
            'libelle'=>'Java',
            'credits'=>4,
            'volumeHoraire'=>30,
            'coefficient'=>3,
            'classe_id'=>1
        ]);
        Matiere::create([
            'libelle'=>'Design',
            'credits'=>3,
            'volumeHoraire'=>24,
            'coefficient'=>1,
            'classe_id'=>1
        ]);
    }
}
