<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AgentServiceExamen extends Model
{
    protected $table = 'agent_service_examens';
    
    protected $fillable = [
        'user_id', 'fonction', 'bureau'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}