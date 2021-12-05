<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectImage extends Model
{
    use HasFactory;

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function room(){
        return $this->belongsTo(Room::class);
    }

    public function style(){
        return $this->belongsTo(Style::class);
    }

    public function budget(){
        return $this->belongsTo(Budget::class);
    }

    public function users(){
        return $this->belongsToMany(User::class, 'favourites', 'project_image_id');
    }
}
