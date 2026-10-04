<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SituationFinanciere extends Model
{
    protected $fillable = ['estAJour', 'dateVerification', 'etudiant_id'];
    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }
}
