<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("bph_members", function (Blueprint $table) {
            $table->id();
            $table->foreignId("pastor_id")->constrained("pastors")->cascadeOnDelete();
            $table->string("name");
            $table->string("position");
            $table->string("photo")->nullable();
            $table->year("period_start");
            $table->year("period_end")->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("bph_members"); }
};