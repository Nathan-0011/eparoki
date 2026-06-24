<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kepala_keluarga', function (Blueprint $table) {
            $table->string('nomor_kk')->nullable()->after('id');
            $table->string('nama_pasangan')->nullable()->after('nama_kk');
            $table->boolean('status_aktif')->default(true)->after('no_telp');
            $table->date('tanggal_bergabung')->nullable()->after('status_aktif');
            $table->string('status_umat')->default('Aktif')->after('tanggal_bergabung');
            $table->text('keterangan')->nullable()->after('status_umat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kepala_keluarga', function (Blueprint $table) {
            $table->dropColumn([
                'nomor_kk',
                'nama_pasangan',
                'status_aktif',
                'tanggal_bergabung',
                'status_umat',
                'keterangan',
            ]);
        });
    }
};
