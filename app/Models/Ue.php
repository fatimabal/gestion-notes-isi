<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ue extends Model
{
    protected $fillable = ['libelle', 'credits', 'semestre_id'];
    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }
    public function modules()
    {
        return $this->hasMany(Matiere::class);
    }
}
