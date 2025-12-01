<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class IncomeChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Pemasukan';

    public ?string $filter = 'daily'; // default

    protected function getFilters(): ?array
    {
        return [
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan',
        ];
    }

    protected function getData(): array
    {
        $now = Carbon::now();
        $query = Transaction::select(
            DB::raw('SUM(amount) as total'),
            DB::raw('DATE(created_at) as date')
        )
        ->where('status', 'success')
        ->groupBy('date')
        ->orderBy('date');

        switch ($this->filter) {
            case 'weekly':
                $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
                break;
            case 'monthly':
                $query->whereMonth('created_at', $now->month);
                break;
            case 'yearly':
                $query->whereYear('created_at', $now->year);
                break;
            default:
                $query->whereDate('created_at', $now->toDateString());
        }

        $data = $query->get();

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan (Rp)',
                    'data' => $data->pluck('total')->map(fn($v) => (float)$v)->toArray(),
                    'backgroundColor' => '#4aa3ff55',
                    'borderColor' => '#4aa3ff',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Bisa diganti 'bar', 'area', dll
    }
}
