<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChefDepartement extends Model
{
    protected $fillable = ['mandat', 'dateDebut', 'dateFin', 'user_id', 'departement_id'];
public function user(){ return $this->belongsTo(User::class); }
public function departement(){ return $this->belongsTo(Departement::class); }
}
