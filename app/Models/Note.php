<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
    'valeur', 'dateSaisie', 'session',
    'etudiant_id', 'enseignant_id',
    'matiere_id', 'evaluation_id',
    'semestre_id',
    'statut', 'type',
    'validee',
    'reclamation',
];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);

    }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);

    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);

    }

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);

    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class);

    }
}
