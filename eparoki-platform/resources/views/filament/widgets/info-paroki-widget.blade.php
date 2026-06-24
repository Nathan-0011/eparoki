<x-filament-widgets::widget>
    <div class="p-4">
        <div class="flex flex-wrap items-center gap-x-8 gap-y-4">

            {{-- Icon --}}
            <div class="flex items-center gap-2 text-primary-600 dark:text-primary-400">
                <x-heroicon-o-building-library class="w-6 h-6" />
                <span class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Informasi Paroki</span>
            </div>

            {{-- Nama Paroki --}}
            <div class="flex items-center gap-2">
                <x-heroicon-o-star class="w-4 h-4 text-yellow-500 flex-shrink-0" />
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-none">Nama Paroki</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $profile->name ?? '–' }}</p>
                </div>
            </div>

            {{-- Divider --}}
            <div class="h-8 w-px bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>

            {{-- Keuskupan --}}
            <div class="flex items-center gap-2">
                <x-heroicon-o-map-pin class="w-4 h-4 text-blue-500 flex-shrink-0" />
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-none">Keuskupan</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $profile->diocese ?? '–' }}</p>
                </div>
            </div>

            {{-- Divider --}}
            <div class="h-8 w-px bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>

            {{-- Hari Pesta --}}
            <div class="flex items-center gap-2">
                <x-heroicon-o-calendar-days class="w-4 h-4 text-purple-500 flex-shrink-0" />
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-none">Hari Pesta Pelindung</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $profile->feast_day ?? '–' }}</p>
                </div>
            </div>

            {{-- Divider --}}
            <div class="h-8 w-px bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>

            {{-- Pastor Aktif --}}
            <div class="flex items-center gap-2">
                <x-heroicon-o-user-circle class="w-4 h-4 text-green-500 flex-shrink-0" />
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-none">Pastor Paroki</p>
                    @if ($pastor)
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $pastor->name }}</p>
                    @else
                        <p class="text-sm italic text-gray-400">Belum diatur</p>
                    @endif
                </div>
            </div>

            {{-- Divider --}}
            <div class="h-8 w-px bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>

            {{-- Tahun Berdiri --}}
            <div class="flex items-center gap-2">
                <x-heroicon-o-clock class="w-4 h-4 text-orange-500 flex-shrink-0" />
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-none">Berdiri</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $profile->established_year ?? '–' }}</p>
                </div>
            </div>

        </div>
    </div>
</x-filament-widgets::widget>
