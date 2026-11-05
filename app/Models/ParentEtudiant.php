<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Etudiant;
class ParentEtudiant extends Model
{
     protected $fillable = [
    'user_id', 'etudiant_id', 'telephone', 'profession', 'adresse', 'lienParents',
];
     public function user(){
        return $this->belongsTo(User::class);

    }
     public function etudiant(){
        return $this->belongsTo(Etudiant::class);

    }
}
