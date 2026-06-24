<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("umat_users", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email")->unique();
            $table->string("google_id")->unique()->nullable();
            $table->string("avatar")->nullable();
            $table->foreignId("lingkungan_id")->nullable()->constrained("lingkungan")->nullOnDelete();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("umat_users"); }
};