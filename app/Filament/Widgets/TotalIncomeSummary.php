<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalIncomeSummary extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    
    protected function getStats(): array
    {
        $total = Transaction::where('status', 'success')->sum('amount');

        return [
            Stat::make('Total Pemasukan', 'Rp ' . number_format($total, 0, ',', '.'))
                ->description('Total pemasukan dari semua transaksi sukses')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('info')
                ->extraAttributes([
                    'class' => 'text-3xl font-bold text-blue-400',
                ]),
        ];
    }
}
