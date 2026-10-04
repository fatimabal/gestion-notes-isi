<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    protected $fillable = ['dateCreation', 'motif', 'statut', 'etudiant_id', 'note_id'];
    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }
    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}
