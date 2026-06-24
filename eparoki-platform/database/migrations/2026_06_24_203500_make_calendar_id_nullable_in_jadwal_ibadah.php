<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jadikan calendar_id nullable agar jadwal mandiri bisa disimpan
     * tanpa harus terkait dengan kalender liturgi.
     */
    public function up(): void
    {
        // Drop foreign key constraint dulu sebelum mengubah kolom
        Schema::table('jadwal_ibadah', function (Blueprint $table) {
            $table->foreignId('calendar_id')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_ibadah', function (Blueprint $table) {
            $table->foreignId('calendar_id')
                ->nullable(false)
                ->change();
        });
    }
};
