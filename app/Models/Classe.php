<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Matiere;
class Classe extends Model
{
     protected $table = 'classes';
    protected $fillable = ['nomClasse', 'niveau', 'effectif', 'filiere_id'];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
   
}
