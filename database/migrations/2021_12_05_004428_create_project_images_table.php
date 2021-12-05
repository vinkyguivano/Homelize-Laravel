<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onUpdate('cascade')->onDelete('cascade');
            $table->string('image_path');
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('style_id')->constrained('styles')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('budget_id')->constrained('budgets')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_images');
    }
}
