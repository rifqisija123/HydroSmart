<?php

namespace App\Filament\Resources\Transactions;

use App\Filament\Resources\Transactions\Pages;
use App\Models\Transaction;

use BackedEnum;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\ViewAction;
use Carbon\Carbon;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static \UnitEnum|string|null $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Transaction List';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date & Time')
                    ->dateTime('d M Y H:i:s')
                    ->sortable(),

                Tables\Columns\TextColumn::make('transaction_type')
                    ->label('Transaction Type')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->colors([
                        'success' => 'success',
                        'pending' => 'warning',
                        'failed'  => 'danger',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('code_transactions')
                    ->label('Kode Transaksi')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state) => $state ?? '-'),
            ])

            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->label('Filter Tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Dari'),
                        Forms\Components\DatePicker::make('until')->label('Sampai'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q, $v) => $q->whereDate('created_at', '>=', $v))
                            ->when($data['until'], fn($q, $v) => $q->whereDate('created_at', '<=', $v));
                    }),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'success' => 'Success',
                        'pending' => 'Pending',
                        'failed'  => 'Failed',
                    ]),

                Tables\Filters\SelectFilter::make('ml')
                    ->label('Volume')
                    ->options(fn() => Transaction::pluck('ml', 'ml')->unique()),

                Tables\Filters\SelectFilter::make('drink')
                    ->label('Minuman')
                    ->options([
                        'kopi' => 'Kopi',
                        'teh'  => 'Teh',
                    ]),

                Tables\Filters\SelectFilter::make('issuer')
                    ->options(fn() => Transaction::pluck('issuer', 'issuer')->filter()->unique()),
            ])

            ->searchable(['code_transactions', 'ml'])
            ->actions([
                ViewAction::make()
                    ->label('Show')
                    ->url(fn($record) => TransactionResource::getUrl('show', ['record' => $record]))
                    ->color('primary')
                    ->icon('heroicon-o-eye'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'show'  => Pages\ViewTransaction::route('/{record}'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Fieldset::make('Detail Transaksi')
                ->columnSpanFull()
                ->schema([
                    TextInput::make('order_id')->label('Order ID')->disabled(),
                    // TextInput::make('code_transactions')->label('Kode Transaksi')->disabled(),
                    TextInput::make('transaction_type')->label('Transaction Type')->disabled(),
                    TextInput::make('status')->label('Status')->disabled(),
                    TextInput::make('ml')->label('Volume (ml)')->disabled(),
                    TextInput::make('drink')->label('Minuman')->disabled(),
                    TextInput::make('amount')->label('Total Pembayaran')->prefix('Rp')
                        ->formatStateUsing(
                            fn($state) =>
                            number_format($state, 0, ',', '.')
                        )->disabled(),
                    TextInput::make('issuer')->label('Issuer')->disabled(),
                    TextInput::make('created_at')->label('Date & Time')
                        ->formatStateUsing(
                            fn($state) =>
                            Carbon::parse($state)
                                ->timezone(config('app.timezone'))
                                ->format('d M Y H:i:s')
                        )->disabled(),
                ])
                ->columns([
                    'default' => 1,
                    'sm'      => 2,
                    'md'      => 2,
                    'lg'      => 2,
                    'xl'      => 2,
                ])
        ]);
    }
}
