<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'typeEvaluation', 'dateEvaluation', 'noteMax', 'noteMin', 'coefficient', 'matiere_id',
    ];

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);

    }
}
