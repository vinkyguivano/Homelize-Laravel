<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectImageController extends Controller
{
    //
    public function getFilters(){
        $budgets = DB::table('budgets')->select('id', 'name')->get();
        $rooms =  DB::table('rooms')->select('id', 'name')->get();
        $styles = DB::table('styles')->select('id', 'name')->get();
        $data = [
            'budget' => $budgets,
            'rooms' => $rooms,
            'style' => $styles
        ];
        return response()->json($data ,200);
    }

    public function getImages(Request $request){
        $budgetId = $request->query('bid');
        $styleId = $request->query('sid');
        $roomdId = $request->query('rid');
        $q = $request->query('q');

        $images = DB::table('project_images')
                    ->leftJoin('projects', 'projects.id', '=', 'project_images.project_id')
                    ->join('professionals', 'professionals.id', 'projects.professional_id')
                    ->leftJoin('rooms', 'project_images.room_id', 'rooms.id')
                    ->leftJoin('styles', 'project_images.style_id', 'styles.id')
                    ->select('project_images.*', 
                    'professionals.name as professional_name',
                    'professionals.image_path as professional_image')
                    ->when($budgetId, function($query, $budgetId){
                        return $query->where('budget_id', $budgetId);
                    })
                    ->when($styleId, function($query, $styleId){
                        return $query->where('style_id', $styleId);
                    })
                    ->when($roomdId, function($query, $roomdId){
                        return $query->where('room_id', $roomdId);
                    })
                    ->when($q, function($query, $q){
                        return $query
                        ->whereRaw('project_images.name like ? or styles.name like ? or rooms.name like ? or professionals.name like ?', 
                        [
                            '%'.$q.'%',
                            '%'.$q.'%',
                            '%'.$q.'%',
                            '%'.$q.'%'
                        ]);
                    })
                    ->orderBy('id', 'asc')
                    ->paginate(10)->withQueryString();
    

        return response()->json($images, 200);
    }

    public function getImageDetail($id){
        $obj = DB::table('project_images', 'p')
                ->selectRaw('
                    p.id,
                    p.image_path,
                    p.name,
                    p.description,
                    p.budget_id,
                    p.project_id,
                    JSON_OBJECT("id", styles.id, "name", styles.name) as style,
                    JSON_OBJECT("id", rooms.id, "name", rooms.name) as room,
                    JSON_OBJECT("id" , professionals.id, 
                    "name", professionals.name, 
                    "image_path", professionals.image_path) as professional,
                    JSON_OBJECT("id", projects.id, "name", projects.name) as project
                ')
                ->join('styles', 'styles.id', 'p.style_id')
                ->join('rooms', 'rooms.id', 'p.room_id')
                ->join('projects', 'projects.id', 'p.project_id')
                ->join('professionals', 'professionals.id', 'projects.professional_id')
                ->where('p.id', $id)
                ->first();

        $this->jsonDecode($obj);
        

        $obj2 = DB::table('project_images', 'pi')
                ->selectRaw('
                    CONCAT( "[", 
                    GROUP_CONCAT( JSON_OBJECT(
                    "id", pi.id,
                    "name", pi.name,
                    "image_path", pi.image_path,
                    "project_id", pi.project_id)), 
                    "]" ) as other
                ')
                ->where('pi.id', "!=", $id)
                ->where('pi.project_id', $obj->project->id)
                ->first();

        $this->jsonDecode($obj2);

        $obj->project->related_images = $obj2->other;

        return response()->json($obj, 200);
    }

    public function jsonDecode($obj){
        foreach($obj as $key => $value){
            if($temp = json_decode($value)){
                $obj->$key = $temp;
            }
        }
    }
}
