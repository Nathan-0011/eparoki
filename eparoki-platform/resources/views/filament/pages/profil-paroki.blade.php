<x-filament-panels::page>

    {{-- SECTION A: Form Edit --}}
    <form wire:submit="save">
        {{ $this->form }}
    </form>

    <hr class="my-6 border-gray-200 dark:border-gray-700">

    {{-- SECTION B: Preview Profil Paroki --}}
    <h3 class="text-xl font-bold mb-4">Preview Profil Saat Ini</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Kolom Info Utama --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-start gap-4 mb-6">
                    @if($profile->logo)
                        <img src="{{ Storage::url($profile->logo) }}" alt="Logo Paroki" class="w-20 h-20 object-contain">
                    @else
                        <div class="w-20 h-20 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center text-primary-600 dark:text-primary-400">
                            <x-heroicon-o-building-office class="w-10 h-10" />
                        </div>
                    @endif
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $profile->name }}</h2>
                        <p class="text-lg text-primary-600 dark:text-primary-400 font-medium">{{ $profile->diocese }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Tahun Berdiri</p>
                        <p class="font-medium">{{ $profile->established_year ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Hari Pesta Pelindung</p>
                        <p class="font-medium">{{ $profile->feast_day ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Nomor Telepon</p>
                        <p class="font-medium">{{ $profile->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Email</p>
                        <p class="font-medium">{{ $profile->email ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-gray-500 dark:text-gray-400">Website</p>
                        @if($profile->website)
                            <a href="{{ $profile->website }}" target="_blank" class="text-primary-600 hover:underline">{{ $profile->website }}</a>
                        @else
                            <p class="font-medium">-</p>
                        @endif
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-gray-500 dark:text-gray-400">Alamat</p>
                        <p class="font-medium">{{ $profile->address ?? '-' }}</p>
                    </div>
                </div>

                @if($profile->description)
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 prose dark:prose-invert max-w-none">
                    {!! $profile->description !!}
                </div>
                @endif
            </div>
        </div>

        {{-- Kolom Sidebar (Pastor & Maps) --}}
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <x-heroicon-o-user-circle class="w-5 h-5 text-primary-500" />
                    Pastor Paroki Saat Ini
                </h3>
                
                @if($pastorAktif)
                    <div class="flex items-center gap-4">
                        @if($pastorAktif->photo)
                            <img src="{{ Storage::url($pastorAktif->photo) }}" class="w-14 h-14 rounded-full object-cover shadow-sm">
                        @else
                            <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                <x-heroicon-o-user class="w-7 h-7 text-gray-400" />
                            </div>
                        @endif
                        <div>
                            <p class="font-bold text-sm text-gray-900 dark:text-white">{{ $pastorAktif->name }}</p>
                            <p class="text-xs text-gray-500">Menjabat sejak {{ $pastorAktif->period_start }}</p>
                        </div>
                    </div>
                @else
                    <div class="p-3 bg-yellow-50 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 rounded-lg text-sm">
                        Belum ada data pastor yang diatur sebagai "Aktif Saat Ini" di menu Pastor Paroki.
                    </div>
                @endif
            </div>

            @if($profile->google_maps_embed)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 border border-gray-200 dark:border-gray-700 overflow-hidden">
                <h3 class="font-bold text-gray-900 dark:text-white mb-3 text-sm">Peta Lokasi</h3>
                <div class="w-full h-48 rounded-lg overflow-hidden relative">
                    {!! $profile->google_maps_embed !!}
                </div>
            </div>
            @endif
        </div>
    </div>

</x-filament-panels::page>
