<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;

class ReportFiltersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['nullable', 'integer'],
            'from'      => ['nullable', 'date'],
            'to'        => ['nullable', 'date', 'after_or_equal:from'],
            'range'     => ['nullable', 'in:today,week,month,year,custom'],
        ];
    }

    public function validatedFilters(): array
    {
        $data = $this->validated();

        // default range
        $range = $data['range'] ?? 'month';

        // If custom, keep from/to, else compute
        if ($range !== 'custom') {
            $now = now();
            [$from, $to] = match ($range) {
                'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
                'week'  => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()],
                'year'  => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
                default => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()], // month
            };

            $data['from'] = $from->toDateString();
            $data['to']   = $to->toDateString();
        } else {
            // custom: fallback to month if not provided
            if (empty($data['from']) || empty($data['to'])) {
                $data['from'] = now()->startOfMonth()->toDateString();
                $data['to']   = now()->endOfMonth()->toDateString();
            }
        }

        return $data;
    }
}
