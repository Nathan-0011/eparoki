<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("song_numbers", function (Blueprint $table) {
            $table->id();
            $table->string("song_number", 4);
            $table->timestamp("sent_at")->useCurrent();
            $table->string("device_id")->nullable();
            $table->enum("status", ["pending","sent","failed"])->default("pending");
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("song_numbers"); }
};