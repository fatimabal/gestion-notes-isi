<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Matiere extends Model
{
    protected $table='modules';
protected $fillable = ['libelle', 'coefficient', 'credits', 'volumeHoraire', 'ue_id'];
   public function ue()
    {
        return $this->belongsTo(Ue::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'matiere_id');
    }

}
