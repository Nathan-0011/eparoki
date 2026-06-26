<?php

namespace App\Filament\Widgets;

use App\Models\SongNumber;
use Filament\Widgets\Widget;

class LatestSongNumberWidget extends Widget
{
    protected static string $view = 'filament.widgets.latest-song-number-widget';
    protected int | string | array $columnSpan = 'half';
    protected static ?string $pollingInterval = '10s';

    protected function getViewData(): array
    {
        return [
            'record' => SongNumber::latest('sent_at')->first(),
        ];
    }
}
