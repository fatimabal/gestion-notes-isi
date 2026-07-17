<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Etudiant;
use App\Models\Semestre;
class Bulletin extends Model
{
    protected $fillable=[
        'session','moyenne_generale','mention',
        'dateGeneration','etudiant_id','semestre_id'
    ];

    public function etudiant(){
           return $this->belongsTo(Etudiant::class);

    }
    public function semestre(){
    return $this->belongsTo(Semestre::class);
}
}

