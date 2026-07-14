<?php

namespace App\Models;

use Dom\Node;
use Illuminate\Database\Eloquent\Model;
use App\Models\Matiere;

class Evaluation extends Model
{
 protected $fillable = [
       'typeEvaluation', 'dateEvaluation', 'noteMax', 'noteMin', 'coefficient','matiere_id'
    ];    
     public function matiere(){
        return $this->belongsTo(Matiere::class);

    }
   
}
