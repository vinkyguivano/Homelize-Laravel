<?php

namespace App\Http\Controllers;

use App\Models\ArchitectureOrder;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    function uploadPhoto(Request $request)
    {
        // $res = CloudinaryStorage::upload($request->photo->getRealPath(), 'test123', 'test');
        return response()->json($request->description, 200);
    }

    public function createOrder(Request $request)
    {
        $userId = $request->user()->id;
        $types = [1, 2];
        $type = $request->query('type');

        if (!in_array($type, $types)) {
            return response()->json(["message" => "Bad Request"], 400);
        }
        // Create Order
        $orderData = $request->data;
        $orderData['user_id'] = $userId;
        $orderData['payment_deadline'] = Carbon::now()->addDay();
        $order = Order::create($orderData);

        if ($type === "1") {
            $orderDetail = $request->detail;
            $mapOrderRoom = $request->mapping_rooms;

            // Create architecture order
            $orderDetail['order_id'] = $order->id;
            ArchitectureOrder::create($orderDetail);

            if ($mapOrderRoom && count($mapOrderRoom) > 0) {
                DB::transaction(function () use ($order, $mapOrderRoom) {
                    foreach ($mapOrderRoom as $m) {
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
        } else if ($type === "2") {
            $mapRooms = $request->mapping_rooms;
            $ids = DB::transaction(function () use ($order, $mapRooms) {
                foreach ($mapRooms as $room) {
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

    public function uploadImage($id, Request $request)
    {
        $types = [1, 2];
        $type = $request->query('type');

        if (!in_array($type, $types)) {
            return response()->json(["message" => "Bad Request"], 400);
        }

        if ($type === "1") {
            $orderId = $id;
            $images = $request->file('images');
            $descriptions = $request->descriptions;
            $fileLength = count($images);
            DB::transaction(function () use ($orderId, $fileLength, $images, $descriptions) {
                for ($i = 0; $i < $fileLength; $i++) {
                    $folder = 'architecture_order';
                    $fileName = 'architecture_order_' . $orderId . '-' . $i + 1;
                    $image_path = CloudinaryStorage::upload($images[$i]->getRealPath(), $fileName, $folder);
                    DB::table('architecture_supporting_images')->insert([
                        "order_id" => $orderId,
                        "image_path" => $image_path,
                        "description" => $descriptions[$i]
                    ]);
                }
            });
            return response()->json(["message" => 'images succesfully created'], 200);
        } else if ($type === "2") {
            $mapId = $id;
            $images = $request->file('images');
            $descriptions = $request->descriptions;
            $fileLength = count($images);
            DB::transaction(function () use ($mapId, $fileLength, $images, $descriptions) {
                for ($i = 0; $i < $fileLength; $i++) {
                    $folder = 'interior_order';
                    $fileName = 'interior_order_' . $mapId . '-' . $i + 1;
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

    public function getOrderList(Request $request){
        $userId = $request->user()->id;
        $statusId = $request->query('sid');

        $res = DB::table('orders', 'o')
                ->leftJoin('professionals', 'professionals.id', 'o.professional_id')
                ->leftJoin('ref_order_statues', 'o.status_id', 'ref_order_statues.id')
                ->selectRaw('
                    o.id,
                    o.professional_id,
                    professionals.name as professional_name,
                    o.name,
                    o.phone_number,
                    o.price,
                    o.created_at,
                    o.status_id as status_id,
                    ref_order_statues.name as status,
                    o.type_id as order_type_id,
                    CASE WHEN type_id = 1 THEN "Desain Arsitektur"
                    WHEN type_id = 2 THEN "Desain Interior" 
                    ELSE NULL END as order_type')
                ->where('o.user_id', '=', $userId)
                ->when($statusId, function($query, $statusId){
                    return $query->where('o.status_id', $statusId);
                })
                ->orderBy("o.id", "desc")
                ->paginate(50);
        return response()->json($res, 200);
    }

    public function getOrderDetail(Order $order)
    {
        if ($order->type_id === 1) {
            $result = DB::table('orders', "o")
                ->leftJoin('professionals', "o.professional_id", "professionals.id")
                ->leftJoin('ref_order_statues', "ref_order_statues.id", "o.status_id")
                ->leftJoin('architecture_orders', "architecture_orders.order_id", "o.id")
                ->leftJoin('map_architecture_order_room', "map_architecture_order_room.order_id", "o.id")
                ->leftJoin('architecture_supporting_images', "architecture_supporting_images.order_id", "o.id")
                ->leftJoin('styles', 'styles.id', 'architecture_orders.style_id')
                ->leftJoin('rooms', 'rooms.id', 'map_architecture_order_room.room_id')
                ->selectRaw('
                            o.id as id,
                            o.name as client_name,
                            o.phone_number as client_phone_number,
                            o.price as price,
                            o.payment_deadline as payment_deadline,
                            o.created_at as created_at,
                            JSON_OBJECT(
                                "id", o.type_id,
                                "name", "Desain Arsitektur"
                            ) as type,
                            JSON_OBJECT(
                                "id", o.status_id,
                                "name", ref_order_statues.name
                            ) as status,
                            JSON_OBJECT(
                                "id", professionals.id,
                                "name", professionals.name,
                                "account_number", professionals.account_number,
                                "email", professionals.email,
                                "phone_number", professionals.phone_number
                            ) as professional,
                            JSON_OBJECT(
                                "land_width", land_width,
                                "land_length", land_length,
                                "building_area", building_area,
                                "budget_estimation", budget_estimation,
                                "floor_count", floor_count,
                                "note", note,
                                "style_name", styles.name,
                                "package_name", CASE WHEN package_id = 1 THEN "Paket Silver" 
                                WHEN package_id = 2 THEN "Paket Gold" ELSE null END,
                                "rooms", CASE WHEN COUNT(map_architecture_order_room.id) > 0 THEN CONCAT( "[", GROUP_CONCAT(
                                    DISTINCT
                                    json_object(
                                        "id", rooms.id,
                                        "name", rooms.name,
                                        "quantity", quantity
                                    )
                                ), "]") ELSE null END,
                                "images", CASE WHEN COUNT(architecture_supporting_images.id) > 0 THEN CONCAT("[", GROUP_CONCAT(
                                    json_object(
                                        "id", architecture_supporting_images.id,
                                        "image_path", architecture_supporting_images.image_path,
                                        "description", architecture_supporting_images.description
                                    )
                                ), "]") ELSE null END
                            ) as detail
                        ')
                ->where('o.id', $order->id)
                ->groupBy('o.id')
                ->first();
            $this->jsonDecode($result);
            $this->jsonDecode($result->detail);
        } else if ($order->type_id === 2) {
            $sub = DB::table('orders', "o")
                ->leftJoin('professionals', "o.professional_id", "professionals.id")
                ->leftJoin('ref_order_statues', "ref_order_statues.id", "o.status_id")
                ->leftJoin('map_interior_order_room', "map_interior_order_room.order_id", "o.id")
                ->leftJoin('room_images', "room_images.map_id", "map_interior_order_room.id")
                ->leftJoin('styles', 'styles.id', 'map_interior_order_room.style_id')
                ->leftJoin('rooms', 'rooms.id', 'map_interior_order_room.room_id')
                ->selectRaw('
                            o.id as id,
                            o.name as client_name,
                            o.phone_number as client_phone_number,
                            o.price as price,
                            o.payment_deadline as payment_deadline,
                            o.created_at as created_at,
                            JSON_OBJECT(
                                "id", o.type_id,
                                "name", "Desain Interior"
                            ) as type,
                            JSON_OBJECT(
                                "id", o.status_id,
                                "name", ref_order_statues.name
                            ) as status,
                            JSON_OBJECT(
                                "id", professionals.id,
                                "name", professionals.name,
                                "account_number", professionals.account_number,
                                "email", professionals.email,
                                "phone_number", professionals.phone_number
                            ) as professional,
                            JSON_OBJECT(
                                "id", map_interior_order_room.id,
                                "room_category", rooms.name,
                                "style_name", styles.name,
                                "room_area", room_area,
                                "room_width", room_width,
                                "room_length", room_length,
                                "note", note,
                                "images", CASE WHEN COUNT(room_images.id) > 0 THEN CONCAT("[", GROUP_CONCAT(
                                    JSON_OBJECT(
                                        "id", room_images.id,
                                        "image_path", room_images.image_path,
                                        "description", room_images.description
                                    )
                                ), "]") ELSE NULL END
                            ) as detail
                        ')
                ->where('o.id', $order->id)
                ->groupBy('o.id', 'map_interior_order_room.id');

            $result = DB::table( DB::raw("({$sub->toSql()}) as sub"))
                        ->mergeBindings($sub)
                        ->select("sub.*", DB::raw('
                            CONCAT("[", GROUP_CONCAT(
                                sub.detail
                            ), "]") as detail
                        '))
                        ->groupBy("sub.id")
                        ->first();

            $this->jsonDecode($result);
            foreach($result->detail as $a){
                $this->jsonDecode($a);
            }
        }

        return response()->json($result, 200);
    }

    public function updateOrder(Request $request, Order $order){
        $orderId = $order->id;
        $type = $request->query('type');
        if(!$type){
            return response()->json(['message' => 'bad request'], 400);
        }

        switch($type){
            /// Melakukan Pembayaran
            case "1":
                $image = $request->file('image');
                $folderName = 'payment';
                $fileName = "bukti_pembayaran_order-".$orderId;
                $image_path = CloudinaryStorage::upload($image->getRealPath(), $fileName, $folderName );
                DB::table('payment_images')->insert([ 'order_id' => $orderId, 'image_path' => $image_path]);
                $order->status_id = 2;
                $order->save();
                return response()->json(['message' => 'order updated successfully'], 200);
                break;
            ///Cancel Order
            case "2":
                $order->status_id = 5;
                $order->save();
                return response()->json(['message' => 'order canceled successfully'], 200);
                break;
            default :
                break;
        }
    }

    public function jsonDecode($obj)
    {
        foreach ($obj as $key => $value) {
            if ($temp = json_decode($value)) {
                $obj->$key = $temp;
            }
        }
    }
}
