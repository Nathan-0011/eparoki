<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("pastors", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("photo")->nullable();
            $table->year("period_start");
            $table->year("period_end")->nullable();
            $table->text("biography")->nullable();
            $table->boolean("is_active")->default(false);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("pastors"); }
};