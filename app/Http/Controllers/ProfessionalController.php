<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfessionalController extends Controller
{

    public function getCities(){
        $list = DB::table('cities')->get();
        return  response()->json($list,200);
    }

    public function getList(Request $request){
        $lat = $request->query('lat');
        $lng = $request->query('lng');
        $sort = $request->query('sort');
        $tid = $request->query('tid');
        $cid = $request->query('cid');
        $q = $request->query('q');

        $orderBy= "p.id";
        $orderSort = "asc";
        if($lat && $lng){
            $orderBy = "km";
        }
        if($sort == 1){
            $orderBy = "total_rating";
            $orderSort = "desc";
        }
        if($sort == 2){
            $orderBy = "total_transaction";
            $orderSort = "desc";
        }

        $sqlDistance = '( 6371 * acos( cos( radians(?) ) 
        * cos( radians( x(p.location) ) ) 
        * cos( radians( y(p.location) ) 
        - radians(?) ) 
        + sin( radians(?) ) 
        * sin( radians( x(p.location) ) ) ) )';
        
        $result = DB::table('professionals', 'p')
                    ->leftJoin('cities', 'p.city_id', 'cities.id')
                    ->leftJoin('professional_types', 'p.professional_type_id', 'professional_types.id')
                    ->leftJoin('orders', 'p.id', 'orders.professional_id')
                    ->leftJoin('ratings', 'ratings.order_id', 'orders.id')
                    ->selectRaw("
                        p.id,
                        p.name, 
                        p.image_path,
                        p.thumbnail,
                        p.city_id as city_id, 
                        cities.name as city_name,
                        cities.province_name,
                        p.professional_type_id,
                        professional_types.name as type_name,
                        COUNT(orders.id) as total_transaction,
                        CASE
                         WHEN AVG(ratings.rating) IS NULL THEN NULL
                         ELSE ROUND(AVG(ratings.rating), 1)
                        END as total_rating
                    ")
                    ->selectRaw("
                        CASE
                            WHEN ? IS NULL or ? IS NULL THEN NULL
                            ELSE ROUND({$sqlDistance}, 3)
                        END AS km
                    ", [$lat, $lng, $lat, $lng, $lat])
                    ->when($tid, function($query, $tid){
                        return $query->whereRaw('(professional_types.id = ? or professional_types.id = 3)', [$tid]);                
                    })
                    ->when($cid, function($query, $cid){
                        return $query->where('city_id', $cid);
                    })
                    ->when($q, function($query, $q){
                        return $query->whereRaw('(p.name like ? or cities.name like ? or professional_types.name like ?)', [
                            '%'.$q.'%',
                            '%'.$q.'%',
                            '%'.$q.'%'
                        ]);
                    })
                    ->where('p.status_id', '=', 2)
                    ->groupBy("p.id")
                    ->orderBy($orderBy, $orderSort)
                    ->paginate(10);
                 
        return response()->json($result, 200);
    } 

    public function getDetail($id){
        $res = DB::table('professionals', 'p')
                ->join('cities', 'p.city_id', 'cities.id')
                ->join('professional_types', 'professional_types.id', 'p.professional_type_id')
                ->leftJoin('orders', 'orders.professional_id', 'p.id')
                ->leftJoin('ratings', 'ratings.order_id', 'orders.id')
                ->selectRaw('
                    p.id as id,
                    p.name as name,
                    p.email as email,
                    p.phone_number as phone_number,
                    p.address as address,
                    JSON_OBJECT(
                        "id", p.city_id,
                        "name", cities.name,
                        "province_name", cities.province_name
                    ) as city,
                    JSON_OBJECT(
                        "latitude", x(p.location),
                        "longitude", y(p.location)
                    ) as location,
                    p.description,
                    p.account_number,
                    p.image_path as profile_pic,
                    p.thumbnail as cover_pic,
                    professional_types.id as type_id,
                    professional_types.name as type_name,
                    CASE
                        WHEN AVG(ratings.rating) IS NULL THEN NULL
                        ELSE ROUND(AVG(ratings.rating), 1)
                    END as total_rating,
                    COUNT(ratings.rating) as count_rating
                ')
                ->where("p.id", $id)
                ->groupBy('p.id')
                ->first();
        
            $res2 = DB::table('professionals', 'p')
                    ->leftJoin('projects', 'p.id', 'projects.professional_id')
                    ->join('project_images', 'projects.id', 'project_images.project_id')
                    ->selectRaw('
                        projects.id as id,
                        projects.name as name,
                        project_images.image_path,
                        projects.year as year
                    ')
                    ->where('p.id', $id)
                    ->groupBy('id')
                    ->get();

            $res->projects = $res2;
            $this->jsonDecode($res);
        
        return response()->json($res, 200);
    }

    public function jsonDecode($obj){
        foreach($obj as $key => $value){
            if($temp = json_decode($value)){
                $obj->$key = $temp;
            }
        }
    }

    public function getRating(Request $request, $id){
        $rating = $request->query("rating");

        $res = DB::table("professionals", "p")
                    ->join("orders", "orders.professional_id", "p.id")
                    ->join("ratings", "ratings.order_id", "orders.id")
                    ->join("users", "users.id", "orders.user_id")
                    ->selectRaw("
                        users.name as username,
                        ratings.rating as rating,
                        ratings.review as review
                    ")
                    ->where("p.id", $id)
                    ->whereRaw("(ratings.review is not null or ratings.review != '')")
                    ->when($rating, function($query, $rating){
                        return $query->where('ratings.rating', $rating);
                    })
                    ->paginate(10);

        return response()->json($res, 200);
    }

    public function updateProfessional(Request $request, $id){
        $accountNumber = $request->account_number;
        if(DB::table('professionals')->where('account_number', $accountNumber)
            ->where('professionals.id', '<>', $id)->exists()){
                return response()->json('account number has been used', 500);
        }

        $request['location'] = DB::raw("(GeomFromText('POINT(".$request->location['latitude']." ".$request->location['longitude'].")'))");

        DB::table('professionals')
            ->where('id', $id)
            ->update($request->all());

        return response()->json(['message' => 'updated succesfully'], 200);
    }

    public function uploadImage(Request $request, $id){
        $fields = array();
        $professional = Professional::findOrFail($id);
        if($request->has('profile_image')){
            $image = $request->profile_image->getRealPath();
            $filename= 'profile_pic_professional_id_'.$professional->id;
            $direktori = 'profile_pic';
            if($professional->image_path){
                $image_path = CloudinaryStorage::replace($professional->image_path, $image, $filename, $direktori);
            }else{
                $image_path = CloudinaryStorage::upload($image, $filename, $direktori);
            }

            $fields['image_path'] = $image_path;
        }

        if($request->has('cover_image')){
            $image = $request->cover_image->getRealPath();
            $filename= 'cover_pic_professional_id_'.$professional->id;
            $direktori = 'cover_pic';
            if($professional->thumbnail){
                $thumbnail = CloudinaryStorage::replace($professional->thumbnail, $image, $filename, $direktori);
            }else{
                $thumbnail = CloudinaryStorage::upload($image, $filename, $direktori);
            }
            $fields['thumbnail'] = $thumbnail;
        }

        DB::table('professionals')->where('id', $id)->update($fields);
        return response()->json(['message' => 'image uploaded succesfully'], 200);
    }

    public function profileComplete($id){
        $professional = Professional::findOrFail($id);
        $professional->status_id = 2;
        $professional->save();

        return response()->json('[message => status updated successfully]', 200);
    }
}
