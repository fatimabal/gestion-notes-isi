<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBulletin extends Model
{
    protected $fillable = [
        'bulletin_id', 'ue_id', 'moyenne_ue',
        'credits_obtenus', 'valide', 'appreciation',
    ];

    public function bulletin() { return $this->belongsTo(Bulletin::class); }
    public function ue() { return $this->belongsTo(Ue::class); }
}