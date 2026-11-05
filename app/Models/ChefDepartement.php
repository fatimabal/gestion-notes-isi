<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChefDepartement extends Model
{
protected $fillable = [
    'user_id', 'departement_id', 'specialite', 'grade', 'mandat', 'dateDebut', 'dateFin',
];
public function user(){ return $this->belongsTo(User::class); }
public function departement(){ return $this->belongsTo(Departement::class); }
}
