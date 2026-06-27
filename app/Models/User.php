<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Etudiant;
use App\Models\Enseignant;
use App\Models\Scolarite;
use App\Models\ParentEtudiant;
use Laravel\Sanctum\HasApiTokens;

// #[Fillable(['name', 'email', 'password'])]
// #[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable;

    protected $fillable = [
        'nom','prenom','email','password','role','telephone',
       
    ];

    protected $hidden = [
        'password','remember_token'
    ];

    public function isScolarite():bool {
        return $this->role==='scolarite';
        }
    public function isEtudiant():bool {
        return $this->role==='etudiant';
        }
    public function isEnseignant():bool {
        return $this->role==='enseignant';
        }
    public function isParent():bool {
        return $this->role==='parent';
        }

    public function etudiant()
    {
        return $this->hasOne(Etudiant::class);
    }
   
    public function enseignant()
    {
        return $this->hasOne(Enseignant::class);
    }
    public function scolarite()
    {
        return $this->hasOne(Scolarite::class);
    }
    public function parentEtudiant()
    {
        return $this->hasOne(ParentEtudiant::class);
    }
}
