<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classe;
class Matiere extends Model
{
    protected $fillable = [
        'libelle','credits','volumeHoraire','coefficient', 'classe_id' 
    ];
    public function classe(){
        return $this->belongsTo(Classe::class);

    }
}
