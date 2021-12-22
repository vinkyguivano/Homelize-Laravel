<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function jsonDecode($obj){
        foreach($obj as $key => $value){
            if($temp = json_decode($value)){
                $obj->$key = $temp;
            }
        }
    }

    public function getDetail($id){
        $res = DB::table('projects', 'p')
                ->join('project_images', 'p.id', 'project_images.project_id')
                ->join('rooms', 'rooms.id', 'project_images.room_id')
                ->join('styles', 'styles.id', 'project_images.style_id')
                ->selectRaw('
                    p.id as id,
                    p.name as name,
                    p.description as description,
                    p.year as year,
                    CONCAT( "[", 
                    GROUP_CONCAT( JSON_OBJECT( 
                    "id", project_images.id,
                    "image_path", project_images.image_path,
                    "description", project_images.description,
                    "room", rooms.name,
                    "style", styles.name
                    )), "]" ) as images
                ')
                ->where('p.id', $id)
                ->first();
        $this->jsonDecode($res);

        return response()->json($res, 200);
    }
}
