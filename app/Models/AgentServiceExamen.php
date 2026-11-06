<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentServiceExamen extends Model
{
    protected $table = 'agent_service_examens';

    protected $fillable = [
        'user_id', 'fonction', 'bureau',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
