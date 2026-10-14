<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deliberation extends Model
{
    protected $fillable = ['classe_id', 'semestre_id', 'validee_par', 'statut', 'date_validation'];

    public function classe()    { return $this->belongsTo(Classe::class); }
    public function semestre()  { return $this->belongsTo(Semestre::class); }
    public function validateur(){ return $this->belongsTo(User::class, 'validee_par'); }
}