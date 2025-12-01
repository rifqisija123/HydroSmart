<?php

namespace App\Filament\Resources\DrinkPrices\Pages;

use App\Filament\Resources\DrinkPrices\DrinkPriceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDrinkPrice extends EditRecord
{
    protected static string $resource = DrinkPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
