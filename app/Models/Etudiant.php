<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ParentEtudiant;
use App\Models\Inscription; 
class Etudiant extends Model
{
     protected $fillable = [
    'user_id', 'matricule', 'dateNaissance', 'lieuNaissance', 'filiere',
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
   public function parents()
{
    return $this->belongsToMany(\App\Models\ParentEtudiant::class, 'etudiant_parent', 'etudiant_id', 'parent_id');
}
}
