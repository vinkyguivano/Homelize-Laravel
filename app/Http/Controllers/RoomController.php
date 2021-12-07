<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function getRooms(){
        $rooms = DB::table('rooms')->select('id', 'name')->get();

        return response()->json($rooms, 200);
    }
}
