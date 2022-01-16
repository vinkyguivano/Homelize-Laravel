<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getUserDetail($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }

    public function updateUser($id, Request $request)
    {
        $data['name'] = $request['name'];

        if ($request->file('photo')) {
            $image = $request->file('photo')->getRealPath();
            $fileName = 'user-' . $id;
            $folder = 'user_pic';
            $image_path = CloudinaryStorage::upload($image, $fileName, $folder);
            $data['image_path'] = $image_path;
        }

        User::where('id', $id)->update($data);
        return response()->json(['message' => 'success'], 200);
    }

    public function chatUpdate(Request $request, $ref)
    {
        DB::table('chat_rooms')->updateOrInsert(
            [
                "user_id" => $request->user_id,
                "professional_id" => $request->professional_id,
                "ref" => $ref
            ],
            [
                "last_message" => $request->last_message,
            ]
        );

        return response()->json("Success", 200);
    }

    public function getUserChat(Request $request, $id)
    {
        $type = $request->query('type');
        $types = [1, 2];
        if (!in_array($type, $types)) {
            return response()->json(["message" => "Bad Request"], 400);
        }

        if($type === "1"){
            $whereClause = ["user_id" => $id];
            $joinTable = "professionals";
            $primaryKey = "professionals.id";
            $foreignKey = "cr.professional_id";
        }else{
            $whereClause = ["professional_id" => $id];
            $joinTable = "users";
            $primaryKey = "users.id";
            $foreignKey = "cr.user_id";
        }

        $result = DB::table('chat_rooms', 'cr')
                    ->join($joinTable, $primaryKey, $foreignKey)
                    ->selectRaw("
                      {$primaryKey} as id,
                      name as name,
                      image_path as image_path,
                      last_message,
                      cr.updated_at")
                    ->where($whereClause)
                    ->orderBy("cr.updated_at", 'desc')
                    ->paginate(30);
        
        return response()->json($result,200);
    }
}
