<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Etudiant;
use App\Models\Classe;
class Inscription extends Model
{
    protected $fillable = [
        'anneeAcademique','dateInscription','Groupe','etudiant_id','classe_id',
    ];

    public function etudiant(){
        return $this->belongsTo(Etudiant::class);
    }
    public function Classe(){
        return $this->belongsTo(Classe::class);
    }

}
