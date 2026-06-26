<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SongNumberResource\Pages;
use App\Models\SongNumber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\FontFamily;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;

class SongNumberResource extends Resource
{
    protected static ?string $model = SongNumber::class;
    protected static ?string $navigationIcon = 'heroicon-o-musical-note';
    protected static ?string $navigationGroup = 'IoT & Display';
    protected static ?string $navigationLabel = 'Nomor Lagu';
    protected static ?string $modelLabel = 'Nomor Lagu';
    protected static ?string $pluralModelLabel = 'Riwayat Nomor Lagu';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Kirim Nomor Lagu ke Display')
                    ->schema([
                        Placeholder::make('info')
                            ->hiddenLabel()
                            ->content(new HtmlString('<div class="p-4 bg-info-50 text-info-700 dark:bg-info-900/30 dark:text-info-300 rounded-lg text-sm">Masukkan nomor lagu yang akan ditampilkan di layar digital gereja. Nomor akan dikirim otomatis ke perangkat display saat disimpan.</div>')),
                            
                        Forms\Components\TextInput::make('song_number')
                            ->label('Nomor Lagu')
                            ->required()
                            ->placeholder('2100')
                            ->maxLength(4)
                            ->minLength(1)
                            ->numeric()
                            ->extraAttributes([
                                'style' => 'font-size: 2rem; font-weight: bold; text-align: center; letter-spacing: 0.5rem;',
                                'maxlength' => '4'
                            ])
                            ->helperText('Masukkan nomor lagu (1-4 digit angka)')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, Forms\Set $set) {
                                if ($state) {
                                    $set('song_number', str_pad($state, 4, '0', STR_PAD_LEFT));
                                }
                            }),

                        Forms\Components\Select::make('device_id')
                            ->label('Perangkat Display Tujuan')
                            ->options([
                                'display-01' => 'Display Utama (Altar)',
                                'display-02' => 'Display Samping Kiri',
                                'display-03' => 'Display Samping Kanan',
                            ])
                            ->default('display-01')
                            ->nullable()
                            ->helperText('Pilih display yang akan menampilkan nomor ini'),
                            
                        Hidden::make('status')->default('pending'),
                        Hidden::make('sent_at')->default(now()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(SongNumber::query()->latest('sent_at')->limit(50))
            ->columns([
                Tables\Columns\TextColumn::make('song_number')
                    ->label('Nomor Lagu')
                    ->size(Tables\Columns\TextColumn\TextColumnSize::Large)
                    ->weight(FontWeight::ExtraBold)
                    ->fontFamily(FontFamily::Mono)
                    ->badge()
                    ->color('primary')
                    ->prefix('# '),

                Tables\Columns\TextColumn::make('device_id')
                    ->label('Display Tujuan')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'display-01' => 'Display Utama (Altar)',
                        'display-02' => 'Display Samping Kiri',
                        'display-03' => 'Display Samping Kanan',
                        default => 'Display Utama (Altar)',
                    })
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status Pengiriman')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'sent' => 'Terkirim ✓',
                        'failed' => 'Gagal ✗',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'sent' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('sent_at')
                    ->label('Dikirim Pada')
                    ->dateTime('d M Y, H:i')
                    ->suffix(' WIB')
                    ->sortable()
                    ->description(fn (SongNumber $record): ?string => 
                        now()->diffInHours($record->sent_at) < 1 
                            ? $record->sent_at->diffForHumans() 
                            : null
                    ),
            ])
            ->defaultSort('sent_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('resend')
                    ->label('Kirim Ulang')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn (SongNumber $record): bool => in_array($record->status, ['failed', 'pending']))
                    ->action(function (SongNumber $record) {
                        $record->update(['status' => 'pending']);
                        
                        try {
                            $isMock = config('iot.mock_mode', true);
                            if ($isMock) {
                                Log::warning('IoT device tidak tersedia, mode simulasi aktif (Kirim Ulang).');
                                $record->update([
                                    'status' => 'sent',
                                    'sent_at' => now(),
                                ]);
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
                            
                            if ($record->status === 'sent') {
                                Notification::make()->title('Berhasil dikirim ulang')->success()->send();
                            } else {
                                Notification::make()->title('Gagal dikirim ulang ke perangkat IoT')->danger()->send();
                            }
                        } catch (\Exception $e) {
                            $record->update([
                                'status' => 'failed',
                                'sent_at' => now(),
                            ]);
                            Notification::make()->title('Gagal: Koneksi Timeout')->danger()->send();
                        }
                    }),
            ])
            ->emptyStateIcon('heroicon-o-musical-note')
            ->emptyStateHeading('Belum Ada Riwayat Nomor Lagu')
            ->emptyStateDescription('Nomor lagu yang dikirim ke display digital gereja akan tercatat di sini')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Kirim Nomor Lagu Pertama')->icon('heroicon-o-plus'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSongNumbers::route('/'),
            'create' => Pages\CreateSongNumber::route('/create'),
        ];
    }
}
