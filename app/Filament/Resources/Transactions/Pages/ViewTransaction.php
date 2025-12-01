<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['transaction_type'] = 'QRIS';
        $data['volume'] = $data['ml'] . ' ml';
        $data['drink']  = ucfirst($data['drink']);
        $data['issuer'] = $data['status'] === 'success'
        ? ($data['issuer'] ?? '-')
        : '-';
        $data['status']  = ucfirst($data['status']);
        // $data['code_transactions'] = $data['code_transactions'] ?? '-';

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(TransactionResource::getUrl('index')),
        ];
    }
}
