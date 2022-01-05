<?php

namespace App\Http\Controllers;

use App\Models\ArchitectureOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    function uploadPhoto(Request $request){
        // $res = CloudinaryStorage::upload($request->photo->getRealPath(), 'test123', 'test');
        return response()->json($request->description, 200);
    }

    public function createOrder(Request $request){
        $userId = $request->user()->id;
        $types = [1, 2];
        $type = $request->query('type');

        if(!in_array($type, $types)){
            return response()->json([ "message" => "Bad Request"], 400);
        }
        // Create Order
        $orderData = $request->data;
        $orderData['user_id'] = $userId;
        $order = Order::create($orderData);

        if($type === "1"){
            $orderDetail = $request->detail;
            $mapOrderRoom = $request->mapping_rooms;

            // Create architecture order
            $orderDetail['order_id'] = $order->id;
            ArchitectureOrder::create($orderDetail);

            if($mapOrderRoom && count($mapOrderRoom) > 0){
                DB::transaction(function () use ($order, $mapOrderRoom) {
                    foreach($mapOrderRoom as $m){
                       DB::table('map_architecture_order_room')
                            ->insert([
                                "order_id" => $order->id,
                                "room_id" => $m['room_id'],
                                "quantity" => $m['quantity']
                            ]);
                    };
                });
            }

            return response()->json([
                "message" =>  "order created succesfully", 
                "order" => $order, 
            ], 200);
        }else if($type === "2" ){
            $mapRooms = $request->mapping_rooms;
            $ids = DB::transaction(function() use ($order, $mapRooms) {
                foreach($mapRooms as $room){
                    $room['order_id'] = $order->id;
                    $id = DB::table('map_interior_order_room')->insertGetId($room);
                    $map_ids[] = $id;
                }

                return $map_ids;
            });
            return response()->json([ 
                "message" => "order created succesfully",
                "order" =>  $order,
                "map_ids" => $ids,
            ], 200);
        }
    }

    public function uploadImage($id, Request $request){
        $types = [1, 2];
        $type = $request->query('type');

        if(!in_array($type, $types)){
            return response()->json([ "message" => "Bad Request"], 400);
        }

        if($type === "1"){
            $orderId = $id;
            $images = $request->file('images');
            $descriptions = $request->descriptions;
            $fileLength = count($images);
            DB::transaction(function() use($orderId, $fileLength, $images, $descriptions){
                for($i = 0 ; $i < $fileLength; $i++){
                    $folder= 'architecture_order';
                    $fileName = 'architecture_order_'.$orderId.'-'.$i + 1;
                    $image_path = CloudinaryStorage::upload($images[$i]->getRealPath(), $fileName, $folder);
                    DB::table('architecture_supporting_images')->insert([
                        "order_id" => $orderId,
                        "image_path" => $image_path,
                        "description" => $descriptions[$i]
                    ]);
                }
            });
            return response()->json(["message" => 'images succesfully created'], 200);
        }else if($type === "2"){
            $mapId = $id;
            $images = $request->file('images');
            $descriptions = $request->descriptions;
            $fileLength = count($images);
            DB::transaction(function() use($mapId, $fileLength, $images, $descriptions){
                for($i = 0 ; $i < $fileLength; $i++){
                    $folder= 'interior_order';
                    $fileName = 'interior_order_'.$mapId.'-'.$i + 1;
                    $image_path = CloudinaryStorage::upload($images[$i]->getRealPath(), $fileName, $folder);
                    DB::table('room_images')->insert([
                        "map_id" => $mapId,
                        "image_path" => $image_path,
                        "description" => $descriptions[$i]
                    ]);
                }
            });
            return response()->json(["message" => 'images succesfully created'], 200);
        }
    }
}
