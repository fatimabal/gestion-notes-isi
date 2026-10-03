<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Matiere extends Model
{
    protected $table='modules';
    protected $fillable = [
    'libelle','credits','volumeHoraire','coefficient' 
    ];
   
}
