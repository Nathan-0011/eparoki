<?php

namespace App\Filament\Resources\SongNumberResource\Pages;

use App\Filament\Resources\SongNumberResource;
use App\Models\SongNumber;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CreateSongNumber extends CreateRecord
{
    protected static string $resource = SongNumberResource::class;

    protected ?string $heading = 'Kirim Nomor Lagu';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['song_number'] = str_pad($data['song_number'], 4, '0', STR_PAD_LEFT);
        $data['status'] = 'pending';
        $data['sent_at'] = now();
        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var SongNumber $record */
        $record = $this->record;
        
        try {
            $isMock = config('iot.mock_mode', true);
            
            if ($isMock) {
                Log::warning('IoT device tidak tersedia, mode simulasi aktif (Create).');
                $record->update(['status' => 'sent', 'sent_at' => now()]);
            } else {
                $response = Http::timeout(config('iot.device_timeout', 5))->post(
                    config('iot.device_url') . '/api/display',
                    [
                        'number' => $record->song_number,
                        'device_id' => $record->device_id ?? 'display-01'
                    ]
                );
                
                $record->update([
                    'status' => $response->successful() ? 'sent' : 'failed',
                    'sent_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            $record->update([
                'status' => 'failed',
                'sent_at' => now(),
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}