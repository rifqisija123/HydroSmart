<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DrinkStatsChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Penjualan Minuman';

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
            'drink',
            DB::raw('SUM(amount) as total_sales'),
            DB::raw('SUM(ml) as total_ml')
        )
        ->where('status', 'success')
        ->groupBy('drink')
        ->orderByDesc('total_sales');

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
                    'label' => 'Total Pemasukan (Rp)',
                    'data' => $data->pluck('total_sales')->map(fn($v) => (float)$v)->toArray(),
                    'backgroundColor' => ['#00d4ff', '#4aa3ff', '#22c55e', '#facc15'],
                    'borderRadius' => 8,
                ],
                [
                    'label' => 'Total Volume (ml)',
                    'data' => $data->pluck('total_ml')->map(fn($v) => (float)$v)->toArray(),
                    'backgroundColor' => ['#2563eb55', '#3b82f655', '#16a34a55', '#eab30855'],
                    'hidden' => true,
                ],
            ],
            'labels' => $data->pluck('drink')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // tampil sebagai grafik batang
    }
}
