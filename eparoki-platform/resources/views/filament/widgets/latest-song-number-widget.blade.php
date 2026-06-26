<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-x-3 mb-4">
            <x-filament::icon
                icon="heroicon-o-musical-note"
                class="w-6 h-6 text-primary-500"
            />
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                Nomor Lagu Terakhir Dikirim
            </h2>
        </div>

        @if($record)
            <div class="flex flex-col items-center justify-center py-6 bg-gray-900 rounded-xl border border-gray-800 shadow-inner relative overflow-hidden">
                <!-- Decorative glow -->
                <div class="absolute inset-0 bg-primary-500/10 blur-xl"></div>
                
                <div class="relative z-10 text-center">
                    <span class="text-white text-6xl font-mono font-bold tracking-[0.5rem] tabular-nums" style="text-shadow: 0 0 20px rgba(var(--primary-500), 0.5);">
                        {{ implode(' ', str_split($record->song_number)) }}
                    </span>
                </div>
            </div>

            <div class="mt-6 space-y-2 text-sm">
                <div class="flex justify-between items-center py-1 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-gray-500 dark:text-gray-400">Display</span>
                    <span class="font-medium text-gray-900 dark:text-gray-200">
                        {{ match($record->device_id) {
                            'display-01' => 'Display Utama (Altar)',
                            'display-02' => 'Display Samping Kiri',
                            'display-03' => 'Display Samping Kanan',
                            default => 'Display Utama (Altar)',
                        } }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-1 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-gray-500 dark:text-gray-400">Status</span>
                    <span class="font-medium inline-flex items-center gap-1.5 {{ match($record->status) {
                        'sent' => 'text-success-600 dark:text-success-400',
                        'failed' => 'text-danger-600 dark:text-danger-400',
                        default => 'text-warning-600 dark:text-warning-400',
                    } }}">
                        @if($record->status === 'sent')
                            <x-filament::icon icon="heroicon-s-check-circle" class="w-4 h-4" /> Terkirim
                        @elseif($record->status === 'failed')
                            <x-filament::icon icon="heroicon-s-x-circle" class="w-4 h-4" /> Gagal
                        @else
                            <x-filament::icon icon="heroicon-s-clock" class="w-4 h-4" /> Menunggu
                        @endif
                    </span>
                </div>
                <div class="flex justify-between items-center py-1">
                    <span class="text-gray-500 dark:text-gray-400">Waktu</span>
                    <span class="font-medium text-gray-900 dark:text-gray-200" title="{{ $record->sent_at?->format('d M Y, H:i:s') }}">
                        {{ $record->sent_at?->diffForHumans() ?? '-' }}
                    </span>
                </div>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-10 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
                <span class="text-gray-400 dark:text-gray-500 text-3xl font-mono font-bold tracking-widest opacity-50">----</span>
                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Belum ada nomor lagu</p>
            </div>
        @endif

        <div class="mt-6 flex justify-center">
            <x-filament::button
                tag="a"
                href="{{ \App\Filament\Resources\SongNumberResource::getUrl('create') }}"
                icon="heroicon-m-plus"
                size="sm"
            >
                Kirim Nomor Baru
            </x-filament::button>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
