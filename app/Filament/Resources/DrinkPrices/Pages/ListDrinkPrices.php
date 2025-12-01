<?php

namespace App\Filament\Resources\DrinkPrices\Pages;

use App\Filament\Resources\DrinkPrices\DrinkPriceResource;
// use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class ListDrinkPrices extends ListRecords
{
    protected static string $resource = DrinkPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Filter::make('tanggal')
                ->form([
                    DatePicker::make('from')
                        ->label('Dari Tanggal')
                        ->placeholder('Pilih tanggal awal'),
                    DatePicker::make('until')
                        ->label('Sampai Tanggal')
                        ->placeholder('Pilih tanggal akhir'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when($data['from'], fn (Builder $q, $date) => $q->whereDate('updated_at', '>=', $date))
                        ->when($data['until'], fn (Builder $q, $date) => $q->whereDate('updated_at', '<=', $date));
                })
                ->label('Filter Tanggal'),
        ];
    }
}
