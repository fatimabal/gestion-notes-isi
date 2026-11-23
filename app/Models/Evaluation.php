<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'typeEvaluation', 'dateEvaluation', 'noteMax', 'noteMin', 'coefficient', 'matiere_id', 'semestre_id',
    ];

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'evaluation_id');
    }
}