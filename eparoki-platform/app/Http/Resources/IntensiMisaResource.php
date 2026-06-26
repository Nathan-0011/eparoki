<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class IntensiMisaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'family_name'        => $this->family_name,
            'amount'             => $this->amount,
            'amount_formatted'   => $this->amount
                ? 'Rp ' . number_format($this->amount, 0, ',', '.')
                : '-',
            'description'        => $this->description,
            'week_date'          => $this->week_date
                ? Carbon::parse($this->week_date)->toDateString()
                : null,
            'week_label'         => $this->week_date
                ? 'Minggu ' . Carbon::parse($this->week_date)->translatedFormat('d F Y')
                : null,
            'is_archived'        => $this->is_archived,
            'created_at'         => $this->created_at?->toIso8601String(),
        ];
    }
}