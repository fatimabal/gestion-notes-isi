<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Etudiant;
use App\Models\Matiere;
class Classe extends Model
{
    protected $fillable = [
    'nomClasse','niveau','anneeAcademique'
    ];
    public function etudiants(){
        return $this->hasMany(Etudiant::class);
    }
     public function matieres(){
        return $this->hasMany(Matiere::class);
    }
   
}
