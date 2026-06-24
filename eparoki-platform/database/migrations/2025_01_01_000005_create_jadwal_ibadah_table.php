<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("jadwal_ibadah", function (Blueprint $table) {
            $table->id();
            $table->foreignId("calendar_id")->constrained("liturgical_calendars")->cascadeOnDelete();
            $table->enum("day_of_week", ["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"]);
            $table->time("time");
            $table->string("type");
            $table->string("celebrant")->nullable();
            $table->string("location")->default("Gereja Santo Fidelis Parapat");
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("jadwal_ibadah"); }
};