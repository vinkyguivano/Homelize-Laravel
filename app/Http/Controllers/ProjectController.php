<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectImage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
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
                    "room", JSON_OBJECT(
                        "id", project_images.room_id,
                        "name", rooms.name
                    ),
                    "style", JSON_OBJECT(
                        "id", project_images.style_id,
                        "name", styles.name
                    ),
                    "maximum_budget", maximum_budget,
                    "minimum_budget", minimum_budget,
                    "budget", JSON_OBJECT(
                        "id", project_images.budget_id
                    )
                    )), "]" ) as images
                ')
                ->where('p.id', $id)
                ->first();
        $this->jsonDecode($res);

        return response()->json($res, 200);
    }

    public function addProject($id, Request $request){
        $projectData = $request->data;
        $projectData['professional_id'] = $id;
        $projectId = DB::table('projects')->insertGetId($projectData);

        $images = $request->images;
        $ids = DB::transaction(function() use($projectId, $images){
            foreach($images as $image){
                $image['project_id'] = $projectId;
                $id = DB::table('project_images')->insertGetId($image);
                $map_ids[] = $id;
            }

            return $map_ids;
        });

        return response()->json([
            'id' => $id,
            'map_ids' => $ids,
            'message'=> 'project created successfully'
        ], 200);
    }

    public function deleteProject($id){
        $project = Project::findOrFail($id);
        $images = $project->projectImages;
        foreach($images as $image){
            CloudinaryStorage::delete($image->image_path, 'project');
        }
        $project->delete();
        return response()->json(["message" => 'project deleted successfully'], 200);
    }

    public function updateProject($id, Request $request){
        $project = Project::findOrFail($id);
        $updatedProjectData = $request->data;
        
        $project->name = $updatedProjectData['name'];
        $project->description = $updatedProjectData['description'];
        $project->year = $updatedProjectData['year'];
        $project->save();

        $updatedProjectImages = $request->images;
        $ids = DB::transaction(function() use($updatedProjectImages, $id){
            foreach($updatedProjectImages as $projectImage){
               if($projectImage['new_image']){
                $projectImage1 = new ProjectImage();
               }else{
                $projectImage1 = ProjectImage::find($projectImage['id']);
               }

               $projectImage1->project_id = $id;
               $projectImage1->description = $projectImage['description'];
               $projectImage1->style_id = $projectImage['style_id'];
               $projectImage1->room_id = $projectImage['room_id'];
               $projectImage1->budget_id = $projectImage['budget_id'];
               $projectImage1->minimum_budget = $projectImage['minimum_budget'];
               $projectImage1->maximum_budget = $projectImage['maximum_budget'];
               $projectImage1->save();

               $map_ids[] = $projectImage1->id;
            }

            return $map_ids;
        });

        $images = $project->projectImages()->whereNotIn('id', $ids)->get();
        foreach($images as $i){
            CloudinaryStorage::delete($i->image_path, 'project');
            $i->delete();
        }

        return response()->json([
            "message"=> "Projek updated successfully",
            "map_ids" => $ids
        ], 200);
    }
}
