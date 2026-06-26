<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classe;
class Matiere extends Model
{
    protected $table='matiere';
    protected $fillable = [
        'libelle','credits','volumeHoraire','coefficient'
    ];
    public function classe(){
        return $this->belongsTo(Classe::class);

    }
}
