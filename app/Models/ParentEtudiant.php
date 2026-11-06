<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentEtudiant extends Model
{
    protected $fillable = [
        'user_id', 'telephone', 'profession', 'adresse', 'lienParents',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);

    }

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);

    }

    public function etudiants()
    {
        return $this->belongsToMany(Etudiant::class, 'etudiant_parent', 'parent_id', 'etudiant_id');
    }
}
