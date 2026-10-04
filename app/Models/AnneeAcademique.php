<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnneeAcademique extends Model
{
    protected $fillable = ['libelle', 'dateDebut', 'dateFin'];
    public function semestres()
    {
        return $this->hasMany(Semestre::class);
    }
}
