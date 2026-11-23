<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bulletin extends Model
{
    protected $fillable = [
        'etudiant_id', 'semestre_id', 'session',
        'moyenne_generale', 'mention', 'dateGeneration',
    ];

    public function etudiant() { return $this->belongsTo(Etudiant::class); }
    public function semestre() { return $this->belongsTo(Semestre::class); }
    public function details() { return $this->hasMany(DetailBulletin::class); }
}