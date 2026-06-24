<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom baru ke tabel jadwal_ibadah
     * tanpa menghapus data yang sudah ada.
     */
    public function up(): void
    {
        Schema::table('jadwal_ibadah', function (Blueprint $table) {
            // Nama ibadah (string eksplisit, misalnya "Misa Natal")
            $table->string('nama_ibadah')->nullable()->after('calendar_id');

            // Jenis ibadah: Harian / Mingguan / Bulanan / Perayaan Khusus
            $table->enum('jenis_ibadah', ['Harian', 'Mingguan', 'Bulanan', 'Perayaan Khusus'])
                ->default('Mingguan')->after('nama_ibadah');

            // Tanggal spesifik (untuk ibadah non-rutin)
            $table->date('tanggal')->nullable()->after('jenis_ibadah');

            // Jam selesai
            $table->time('jam_selesai')->nullable()->after('time');

            // Rename 'time' menjadi 'jam_mulai' sudah ada, biarkan 'time' tetap ada.
            // Kita tambah alias 'jam_mulai' agar bisa digunakan berdampingan.
            // (Kita TIDAK rename agar tidak merusak relasi yang sudah ada)

            // Keterangan tambahan
            $table->text('keterangan')->nullable()->after('location');

            // Status: Aktif / Selesai / Dibatalkan
            $table->enum('status', ['Aktif', 'Selesai', 'Dibatalkan'])
                ->default('Aktif')->after('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_ibadah', function (Blueprint $table) {
            $table->dropColumn(['nama_ibadah', 'jenis_ibadah', 'tanggal', 'jam_selesai', 'keterangan', 'status']);
        });
    }
};
