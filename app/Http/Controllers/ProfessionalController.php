<?php

namespace App\Http\Controllers;

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
                    ->groupBy("p.id")
                    ->orderBy($orderBy, $orderSort)
                    ->paginate(10);
                 
        return response()->json($result, 200);
    } 
}
