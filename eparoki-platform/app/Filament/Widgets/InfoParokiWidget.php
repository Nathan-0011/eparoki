<?php

namespace App\Filament\Widgets;

use App\Models\ParishProfile;
use App\Models\Pastor;
use Filament\Widgets\Widget;

class InfoParokiWidget extends Widget
{
    protected static ?string $heading = null;

    // Tampil di baris terbawah, lebar penuh
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    // View Blade yang akan di-render
    protected static string $view = 'filament.widgets.info-paroki-widget';

    /**
     * Data yang dikirim ke view blade.
     */
    public function getViewData(): array
    {
        return [
            'profile' => ParishProfile::getProfile(),
            'pastor' => Pastor::active()->first(),
        ];
    }
}
