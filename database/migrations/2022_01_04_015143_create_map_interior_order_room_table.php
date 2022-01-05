<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMapInteriorOrderRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('map_interior_order_room', function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_id")->constrained('orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('style_id')->constrained('styles')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('room_area');
            $table->unsignedInteger('room_width');
            $table->unsignedInteger('room_length');
            $table->text('note')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('map_interior_order_room');
    }
}
