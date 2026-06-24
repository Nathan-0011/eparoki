<?php

namespace App\Filament\Resources\JadwalIbadahResource\Pages;

use App\Filament\Resources\JadwalIbadahResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJadwalIbadah extends CreateRecord
{
    protected static string $resource = JadwalIbadahResource::class;

    protected ?string $heading = 'Buat Jadwal Ibadah';

    /**
     * Sinkronkan kolom 'type' (legacy) dengan 'nama_ibadah'
     * sebelum data disimpan ke database.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Isi kolom legacy 'type' dengan nama_ibadah agar kompatibel dengan kode lama
        $data['type'] = $data['nama_ibadah'] ?? $data['type'] ?? 'Misa';
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}