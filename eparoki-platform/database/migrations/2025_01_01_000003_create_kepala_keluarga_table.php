<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("kepala_keluarga", function (Blueprint $table) {
            $table->id();
            $table->foreignId("lingkungan_id")->constrained("lingkungan")->cascadeOnDelete();
            $table->string("nama_kk");
            $table->text("alamat")->nullable();
            $table->integer("jumlah_anggota")->default(1);
            $table->string("no_telp")->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("kepala_keluarga"); }
};