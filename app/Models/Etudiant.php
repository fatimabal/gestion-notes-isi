<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Classe;
use App\Models\ParentEtudiant;
class Etudiant extends Model
{
     protected $fillable = [
        'matricule','dateNaissance','lieuNaissance','filiere',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function classe(){
        return $this->belongsTo(Classe::class);

    }
    public function parentEtudiant(){
        return $this->hasOne(ParentEtudiant::class);
    }
   
}
