<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    protected $table = 'modules';

    protected $fillable = ['libelle', 'coefficient', 'credits', 'volumeHoraire', 'ue_id', 'mcc', 'examen', 'moyenne_module'];    
    
    protected $appends = ['mcc', 'examen', 'moyenne_module'];
    
    protected $guarded = [];
    
    public function ue()
    {
        return $this->belongsTo(Ue::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'matiere_id');
    }

    public function enseignants()
    {
        return $this->belongsToMany(Enseignant::class, 'enseignant_module', 'module_id', 'enseignant_id');
    }

public function getMccAttribute() { return $this->attributes['mcc'] ?? 0; }
public function getExamenAttribute() { return $this->attributes['examen'] ?? 0; }
public function getMoyenneModuleAttribute() { return $this->attributes['moyenne_module'] ?? 0; }
}
