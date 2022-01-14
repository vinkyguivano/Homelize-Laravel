<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Professional extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'created_at',
        'updated_at'
    ];

    public function professionalType(){
        return $this->belongsTo(ProfessionalType::class, 'professional_type_id', 'id');
    }

    public function city(){
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function projects(){
        return $this->hasMany(Project::class, 'professional_id', 'id');
    }

    public function order(){
        return $this->hasMany(Order::class);
    }
}
