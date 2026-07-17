<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Note;
use App\Models\User;
class HistoriqueModification extends Model
{
    protected $fillable=[
        'dateAction','ancienneValeur','nouvelleValeur',
        'typeAction','note_id','user_id'
    ];

    public function note(){
           return $this->belongsTo(Note::class);

    }
    public function user(){
           return $this->belongsTo(User::class);

    }
}

 