@if($jadwals->isEmpty())
    <div class="flex items-center gap-3 p-4 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
        <x-heroicon-o-calendar class="w-5 h-5" />
        <p class="text-sm">Belum ada jadwal ibadah yang tercatat untuk minggu ini.</p>
    </div>
@else
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-3 font-semibold">Hari</th>
                    <th class="px-4 py-3 font-semibold">Waktu</th>
                    <th class="px-4 py-3 font-semibold">Jenis Ibadah</th>
                    <th class="px-4 py-3 font-semibold">Selebran</th>
                    <th class="px-4 py-3 font-semibold">Lokasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($jadwals as $jadwal)
                    <tr class="bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                {{ $jadwal->day_of_week === 'Minggu' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' :
                                   ($jadwal->day_of_week === 'Sabtu' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' :
                                   'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400') }}">
                                {{ $jadwal->day_of_week }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-mono text-gray-700 dark:text-gray-300">
                            {{ \Carbon\Carbon::parse($jadwal->time)->format('H:i') }}
                        </td>
                        <td class="px-4 py-3 text-gray-900 dark:text-white font-medium">{{ $jadwal->type }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $jadwal->celebrant ?? '–' }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $jadwal->location ?? '–' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
