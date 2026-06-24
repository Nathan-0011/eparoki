<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("lingkungan", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("patron_saint")->nullable();
            $table->text("description")->nullable();
            $table->string("photo")->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("lingkungan"); }
};