<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProfessionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professionals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->text('address')->nullable();
            $table->point('location')->nullable();
            $table->text('description')->nullable();
            $table->string('password');
            $table->string('account_number')->unique()->nullable();
            $table->string('image_path')->nullable();
            $table->string('thumbnail')->nullable();
            $table->foreignId('city_id')->nullable()
            ->constrained('cities')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('professional_type_id')->nullable()
            ->constrained('professional_types')
            ->onUpdate('cascade')
            ->onDelete('cascade');
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
        Schema::dropIfExists('professionals');
    }
}
