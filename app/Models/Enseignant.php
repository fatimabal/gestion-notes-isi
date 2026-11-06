<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    protected $fillable = [
        'user_id', 'specialite', 'grade',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);

    }

    public function modules()
    {
        return $this->belongsToMany(Matiere::class, 'enseignant_module', 'enseignant_id', 'module_id');
    }
}
