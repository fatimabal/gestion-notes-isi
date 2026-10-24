<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Note;
use App\Models\Bulletin;
class Semestre extends Model
{
      protected $fillable = ['libelle', 'dateDebut', 'dateFin'];
  
        public function ues()
    {
        return $this->hasMany(Ue::class);
    }

    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class);
    }

}
