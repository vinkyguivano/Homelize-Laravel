<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public function professional(){
        return $this->belongsTo(Professional::class);
    }

    public function projectImages(){
        return $this->hasMany(ProjectImage::class);
    }
}
