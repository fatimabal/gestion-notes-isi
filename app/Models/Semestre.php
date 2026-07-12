<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Note;
use App\Models\Bulletin;
class Semestre extends Model
{
    protected $fillable = [
        'dateDebut','dateFin',
    ];    
    //  public function note(){
    //     return $this->hasMany(Note::class);

    // }
    //   public function bulletin(){
    //     return $this->hasMany(Bulletin::class);

    // }
}
