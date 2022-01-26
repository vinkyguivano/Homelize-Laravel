<?php

namespace App\Http\Controllers;

use App\Models\ArchitectureOrder;
use App\Models\Order;
use App\Models\Rating;
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
        $statusId = $request->query('sid');
        $userId = $request->query('uid');
        $professionalId = $request->query('pid');

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
                ->when($userId, function($query, $userId){
                    return $query->where('o.user_id', '=', $userId);
                })
                ->when($professionalId, function($query, $professionalId){
                    return $query->where('o.professional_id', '=', $professionalId);
                })
                ->when($statusId, function($query, $statusId){
                    $statusId = explode(',', $statusId);
                    return $query->whereIn('o.status_id', $statusId);
                })
                ->orderBy("o.updated_at", "desc")
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
                ->leftJoin('payment_images', 'payment_images.order_id', 'o.id')
                ->leftJoin('users', 'users.id', 'o.user_id')
                ->leftJoin('order_progress', 'order_progress.order_id', 'o.id')
                ->leftJoin('ratings', 'o.id', 'ratings.order_id')
                ->leftJoin('order_complaints', 'o.id', 'order_complaints.order_id')
                ->selectRaw('
                            o.id as id,
                            o.name as client_name,
                            o.phone_number as client_phone_number,
                            o.price as price,
                            o.payment_deadline as payment_deadline,
                            o.created_at as created_at,
                            ratings.rating as rate,
                            ratings.review as review,
                            JSON_OBJECT(
                                "id", o.type_id,
                                "name", "Desain Arsitektur"
                            ) as type,
                            JSON_OBJECT(
                                "id", o.status_id,
                                "name", ref_order_statues.name
                            ) as status,
                            JSON_OBJECT(
                                "id", users.id,
                                "name", users.name,
                                "profile_pic", users.image_path,
                                "email", users.email
                            ) as user,
                            JSON_OBJECT(
                                "id", professionals.id,
                                "name", professionals.name,
                                "account_number", professionals.account_number,
                                "email", professionals.email,
                                "phone_number", professionals.phone_number,
                                "profile_pic", professionals.image_path
                            ) as professional,
                            CASE WHEN COUNT(payment_images.id) > 0 THEN CONCAT("[" , GROUP_CONCAT(
                                DISTINCT
                                json_object(
                                    "id", payment_images.id,
                                    "image_path", payment_images.image_path
                                )
                                order by payment_images.id desc
                            ) , "]") ELSE NULL END as payment_images,
                            CASE WHEN COUNT(order_progress.id) > 0 THEN CONCAT("[" , GROUP_CONCAT(
                                DISTINCT
                                json_object(
                                    "id", order_progress.id,
                                    "name", order_progress.name,
                                    "link", order_progress.file,
                                    "description", order_progress.description,
                                    "url", order_progress.link,
                                    "complaint_id", order_progress.complaint_id,
                                    "created_at", order_progress.created_at 
                                )
                                order by order_progress.id desc
                            ) , "]") ELSE NULL END as order_progress,
                            CASE WHEN COUNT(order_complaints.id) > 0 THEN CONCAT("[" , GROUP_CONCAT(
                                DISTINCT
                                json_object(
                                    "id", order_complaints.id,
                                    "title", order_complaints.title,
                                    "description", order_complaints.description,
                                    "request_image_path", order_complaints.evidence_image_path,
                                    "response", order_complaints.response,
                                    "response_image_path", order_complaints.response_image_path
                                )
                                order by order_progress.id desc
                            ) , "]") ELSE NULL END as order_complaints,
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
                                    DISTINCT
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
                ->leftJoin('payment_images', 'payment_images.order_id', 'o.id')
                ->leftJoin('users', 'users.id', 'o.user_id')
                ->leftJoin('order_progress', 'order_progress.order_id', 'o.id')
                ->leftJoin('ratings', 'o.id', 'ratings.order_id')
                ->leftJoin('order_complaints', 'o.id', 'order_complaints.order_id')
                ->selectRaw('
                            o.id as id,
                            o.name as client_name,
                            o.phone_number as client_phone_number,
                            o.price as price,
                            o.payment_deadline as payment_deadline,
                            o.created_at as created_at,
                            ratings.rating as rate,
                            ratings.review as review,
                            JSON_OBJECT(
                                "id", o.type_id,
                                "name", "Desain Interior"
                            ) as type,
                            JSON_OBJECT(
                                "id", o.status_id,
                                "name", ref_order_statues.name
                            ) as status,
                            JSON_OBJECT(
                                "id", users.id,
                                "name", users.name,
                                "email", users.email,
                                "profile_pic", users.image_path
                            ) as user,
                            JSON_OBJECT(
                                "id", professionals.id,
                                "name", professionals.name,
                                "account_number", professionals.account_number,
                                "email", professionals.email,
                                "phone_number", professionals.phone_number,
                                "profile_pic", professionals.image_path
                            ) as professional,
                            CASE WHEN COUNT(payment_images.id) > 0 THEN CONCAT("[" , GROUP_CONCAT(
                                DISTINCT
                                json_object(
                                    "id", payment_images.id,
                                    "image_path", payment_images.image_path
                                )
                                order by payment_images.id desc
                            ) , "]") ELSE NULL END as payment_images,
                            CASE WHEN COUNT(order_progress.id) > 0 THEN CONCAT("[" , GROUP_CONCAT(
                                DISTINCT
                                json_object(
                                    "id", order_progress.id,
                                    "name", order_progress.name,
                                    "link", order_progress.file,
                                    "description", order_progress.description,
                                    "url", order_progress.link,
                                    "complaint_id", order_progress.complaint_id,
                                    "created_at", order_progress.created_at 
                                )
                                order by order_progress.id desc
                            ) , "]") ELSE NULL END as order_progress,
                            CASE WHEN COUNT(order_complaints.id) > 0 THEN CONCAT("[" , GROUP_CONCAT(
                                DISTINCT
                                json_object(
                                    "id", order_complaints.id,
                                    "title", order_complaints.title,
                                    "description", order_complaints.description,
                                    "request_image_path", order_complaints.evidence_image_path,
                                    "response", order_complaints.response,
                                    "response_image_path", order_complaints.response_image_path
                                )
                                order by order_progress.id desc
                            ) , "]") ELSE NULL END as order_complaints,
                            JSON_OBJECT(
                                "id", map_interior_order_room.id,
                                "room_category", rooms.name,
                                "style_name", styles.name,
                                "room_area", room_area,
                                "room_width", room_width,
                                "room_length", room_length,
                                "note", note,
                                "images", CASE WHEN COUNT(room_images.id) > 0 THEN CONCAT("[", GROUP_CONCAT(
                                    DISTINCT
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
                $order->status_id = 6;
                $order->save();
                return response()->json(['message' => 'order canceled successfully'], 200);
                break;
            ///Accept Payment Confirmation
            case "3":
                $order->status_id = 3;
                $order->save();
                return response()->json(['message' => 'payment confirmed successfully'], 200);
                break;
            ///Reject Payment Confirmation
            case "4":
                $paymentDeadline = Carbon::now()->addDay();
                $order->status_id = 1;
                $order->payment_deadline = $paymentDeadline;
                $order->save();
                return response()->json(['message' => 'payment updated successfully'], 200);
                break;
            //Professional finish Order
            case "5":
                $link = $request['link'];
                $description = 'Kamu dapat melihat hasil akhir pengerjaan desain melalui link ini '.$link;
                DB::table('order_progress')->insert([
                    'order_id' => $orderId,
                    'name' => 'Order selesai oleh Professional',
                    'description' => $description,
                    'link' => $link
                ]);
                $order->status_id = 4;
                $order->save();
                return response()->json(['message' => 'order updated successfully'], 200);
                break;
            //Client finish Order
            case "6":
                DB::table('order_progress')->insert([
                    'order_id' => $orderId,
                    'name' => 'Order selesai oleh Client',
                    'description' => 'Order telah dikonfirmasi selesai oleh Client'
                ]);
                $order->status_id = 5;
                $order->save();
                return response()->json(['message' => 'order updated successfully'], 200);
                break;
            //Client give complaint
            case "7":
                $file = $request->file('image')->getRealPath();
                $folderName = 'complaint';
                $fileName = 'img-complaint-order-'.$orderId;
                $image_path = CloudinaryStorage::upload($file, $fileName, $folderName);
                $complaintId = DB::table('order_complaints')->insertGetId([
                    'order_id' => $orderId,
                    'title' => $request->title,
                    'description' => $request->description,
                    'evidence_image_path' => $image_path
                ]);
                DB::table('order_progress')->insert([
                    'order_id' => $orderId,
                    'name' => 'Client mengajukan komplain',
                    'complaint_id' => $complaintId
                ]);
                $order->status_id = 7;
                $order->save();
                break;
            //Reject Complaint
            case "8":
                $data['response'] = $request->description; 
                if($request->file('image')){
                    $file = $request->file('image');
                    $folderName = 'complaint';
                    $fileName = "img-response-complaint-order".$orderId;
                    $image_path = CloudinaryStorage::upload($file->getRealPath(), $fileName, $folderName);
                    $data['response_image_path'] = $image_path;
                }
                DB::table('order_complaints')->where('id', $request->complaint_id)->update($data);
                DB::table('order_progress')->insert([
                    'order_id' => $orderId,
                    'name' => 'Professional menolak komplain',
                    'complaint_id' => $request->complaint_id
                ]);
                $order->status_id = 4;
                $order->save();
                break;
            default :
                break;
        }
    }

    public function updateOrderProgress(Order $order, Request $request){
        $request->validate([
            'current_update' => 'required|string',
            'file' => 'max:10000',
        ]);

        $data['order_id'] = $order->id;
        $data['name'] = $request['current_update'];

        if($request->file('file')){
            $file = $request->file('file');
            $fileName = time()."_".$file->getClientOriginalName();
            $file->move('uploads', $fileName);
            $data['file'] = $fileName;
        }

        DB::table('order_progress')->insert($data);
        return response()->json(['message' => 'insert successfully'], 200);
    }

    public function addRating(Order $order, Request $request){
        $request->validate([
            "rating" => 'required|integer',
            'review' => 'string'
        ]);

        Rating::firstOrCreate(
            [ 'order_id' => $order->id],
            [
                "rating" => $request['rating'],
                'review' => $request['review']
            ]
        );

        return response()->json('rating inserted successfully', 200);
    }

    public function downloadFile($filename){
        $file_path = public_path('uploads/'.$filename);
        if(file_exists($file_path)){
            return response()->download($file_path, $filename, [
                'Content-Length: '. filesize($file_path)
            ]);
        }else{
            response()->json(['message' => 'file not exist'], 404);
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
