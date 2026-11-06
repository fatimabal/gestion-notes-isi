<?php

namespace Database\Seeders;

use App\Models\Matiere;
use Illuminate\Database\Seeder;

class MatiereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Matiere::create([
            'libelle' => 'Algorithmique',
            'credits' => 3,
            'volumeHoraire' => 30,
            'coefficient' => 2,
        ]);
        Matiere::create([
            'libelle' => 'Java',
            'credits' => 4,
            'volumeHoraire' => 30,
            'coefficient' => 3,
        ]);
        Matiere::create([
            'libelle' => 'Design',
            'credits' => 3,
            'volumeHoraire' => 24,
            'coefficient' => 1,
        ]);
    }
}
