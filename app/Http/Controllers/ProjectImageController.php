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
        $designs = DB::table('styles')->select('id', 'name')->get();
        $data = [
            'budgets' => $budgets,
            'rooms' => $rooms,
            'designs' => $designs
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
                    ->select('project_images.id', 
                    'project_images.image_path',
                    'projects.id as project_id',
                    'projects.name as project_name',
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
                        ->whereRaw('(projects.name like ? or styles.name like ? or rooms.name like ? or professionals.name like ?)', 
                        [
                            '%'.$q.'%',
                            '%'.$q.'%',
                            '%'.$q.'%',
                            '%'.$q.'%'
                        ]);
                    })
                    ->groupBy('project_id')
                    ->paginate(10)->withQueryString();
    
        return response()->json($images, 200);
    }

    public function getImageDetail(Request $request, $id){
        $userId = $request->user()->id;
        $obj = DB::table('project_images', 'p')
                ->selectRaw('
                    p.id,
                    p.image_path,
                    p.description,
                    p.budget_id,
                    p.project_id,
                    CASE WHEN (SELECT 1
                        FROM map_user_images mui 
                        WHERE mui.project_image_id = ? and mui.user_id = ?) THEN true
                        ELSE false
                    END as is_liked,
                    JSON_OBJECT("id", styles.id, "name", styles.name) as style,
                    JSON_OBJECT("id", rooms.id, "name", rooms.name) as room,
                    JSON_OBJECT("id" , professionals.id, 
                    "name", professionals.name, 
                    "image_path", professionals.image_path,
                    "city", cities.name,
                    "province", cities.province_name ) as professional,
                    JSON_OBJECT("id", projects.id, "name", projects.name) as project
                ', [$id, $userId])
                ->join('styles', 'styles.id', 'p.style_id')
                ->join('rooms', 'rooms.id', 'p.room_id')
                ->join('projects', 'projects.id', 'p.project_id')
                ->join('professionals', 'professionals.id', 'projects.professional_id')
                ->join('cities', 'cities.id', 'professionals.city_id')
                ->where('p.id', $id)
                ->first();

        $this->jsonDecode($obj);
        

        $obj2 = DB::table('project_images', 'pi')
                ->selectRaw('
                    CONCAT( "[", 
                    GROUP_CONCAT( JSON_OBJECT(
                    "id", pi.id,
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

    public function createUserImage(Request $request){
        $userId = $request->user()->id;
        $imageId = $request->get('image_id');

        $res = DB::table('map_user_images')->insert([
            'user_id' => $userId, 'project_image_id' => $imageId
        ]);

        return response()->json($res,200);
    }

    public function deleteUserImage(Request $request, $id){
        $userId = $request->user()->id;

        DB::table('map_user_images')
            ->where('user_id', $userId)
            ->where('project_image_id', $id)
            ->delete();
        
        return response()->json(['message' => 'deleted succesfully'], 200);
    }

    public function getUserImages(Request $request){
        $userId = $request->user()->id;

        $result = DB::table('map_user_images', 'mui')
                    ->join('project_images', 'project_images.id', 'mui.project_image_id')
                    ->join('projects', 'projects.id', 'project_images.project_id')
                    ->join('professionals', 'professionals.id', 'projects.professional_id')
                    ->selectRaw('
                        mui.project_image_id as id,
                        project_images.image_path as image_path,
                        projects.name as project_name,
                        professionals.name as professional_name,
                        professionals.image_path as professional_image
                    ')
                    ->where('mui.user_id', $userId)
                    ->paginate(10)->withQueryString();
        
        return response()->json($result, 200);

    }
}
