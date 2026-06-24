<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("banners", function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("image_path");
            $table->boolean("is_active")->default(true);
            $table->date("start_date")->nullable();
            $table->date("end_date")->nullable();
            $table->integer("order")->default(0);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("banners"); }
};