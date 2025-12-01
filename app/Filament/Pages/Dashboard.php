<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\TotalIncomeSummary;
use App\Filament\Widgets\IncomeChart;
use App\Filament\Widgets\DrinkStatsChart;
use BackedEnum;

class Dashboard extends BaseDashboard
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-home';

    public function getWidgets(): array
    {
        return [
            TotalIncomeSummary::class,
            DrinkStatsChart::class,
            IncomeChart::class,
        ];
    }

    public function getColumns(): int|array
    {
        return [
            'sm' => 1,
            'md' => 1,
            'lg' => 2,
            'xl' => 2,
        ];
    }
}
