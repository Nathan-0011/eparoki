<?php

namespace App\Filament\Resources\IntensiMisaResource\Pages;

use App\Filament\Resources\IntensiMisaResource;
use App\Filament\Widgets\IntensiMisaSummaryWidget;
use App\Models\IntensiMisa;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListIntensiMisas extends ListRecords
{
    protected static string $resource = IntensiMisaResource::class;

    protected ?string $heading = 'Daftar Intensi Misa';

    // Tampilkan IntensiMisaSummaryWidget di atas tabel
    protected function getHeaderWidgets(): array
    {
        return [
            IntensiMisaSummaryWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            // Tombol Reset Minggu Ini
            Actions\Action::make('reset_minggu')
                ->label('Reset Minggu Ini')
                ->icon('heroicon-o-arrow-path')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Reset Intensi Misa Minggu Ini?')
                ->modalDescription('Semua intensi minggu ini akan diarsipkan dan tidak akan tampil lagi di daftar aktif. Data tidak dihapus, hanya dipindahkan ke arsip. Tindakan ini tidak dapat dibatalkan.')
                ->modalSubmitActionLabel('Ya, Arsipkan Sekarang')
                ->modalCancelActionLabel('Batal')
                ->action(function () {
                    $seninMingguIni = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();

                    $jumlah = IntensiMisa::where('week_date', $seninMingguIni)
                        ->where('is_archived', false)
                        ->count();

                    if ($jumlah === 0) {
                        Notification::make()
                            ->title('Tidak ada intensi aktif')
                            ->body('Tidak ada intensi aktif minggu ini yang perlu direset.')
                            ->info()
                            ->send();
                        return;
                    }

                    IntensiMisa::where('week_date', $seninMingguIni)
                        ->where('is_archived', false)
                        ->update(['is_archived' => true]);

                    Notification::make()
                        ->title("{$jumlah} intensi misa berhasil diarsipkan")
                        ->success()
                        ->send();
                }),

            // Tombol Tambah
            Actions\CreateAction::make()
                ->label('Tambah Intensi Misa')
                ->icon('heroicon-o-plus'),
        ];
    }

    // Tab: Minggu Ini vs Arsip
    public function getTabs(): array
    {
        $countAktif = IntensiMisa::where('is_archived', false)->count();
        $countArsip = IntensiMisa::where('is_archived', true)->count();

        return [
            'aktif' => Tab::make('Minggu Ini')
                ->badge($countAktif)
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_archived', false)
                    ->orderBy('created_at', 'desc')),

            'arsip' => Tab::make('Arsip')
                ->badge($countArsip)
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_archived', true)
                    ->orderBy('week_date', 'desc')),
        ];
    }
}