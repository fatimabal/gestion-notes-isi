<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scolarite extends Model
{
    protected $fillable = [
        'fonction', 'bureau',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);

    }
}
