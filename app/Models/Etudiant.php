<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ParentEtudiant;
use App\Models\Inscription; 
class Etudiant extends Model
{
     protected $fillable = [
        'matricule','dateNaissance','lieuNaissance','filiere',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function inscription(){
        return $this->hasMany(Inscription::class);

    }
    public function parentEtudiant(){
        return $this->hasOne(ParentEtudiant::class);
    }
   
}
