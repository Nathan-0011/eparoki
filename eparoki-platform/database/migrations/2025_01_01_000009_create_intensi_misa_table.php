<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("intensi_misa", function (Blueprint $table) {
            $table->id();
            $table->string("family_name");
            $table->bigInteger("amount")->nullable();
            $table->text("description");
            $table->date("week_date");
            $table->boolean("is_archived")->default(false);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("intensi_misa"); }
};