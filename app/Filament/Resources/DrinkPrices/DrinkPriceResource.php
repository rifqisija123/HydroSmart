<?php

namespace App\Filament\Resources\DrinkPrices;

use App\Models\DrinkPrice;
use App\Models\Transaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ButtonAction;

class DrinkPriceResource extends Resource
{
    protected static ?string $model = DrinkPrice::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-beaker';
    protected static \UnitEnum|string|null $navigationGroup = 'Pengaturan Harga';
    protected static ?string $navigationLabel = 'Minuman';
    protected static ?string $pluralModelLabel = 'Minuman';

    /** ✅ Menggunakan Schema (bukan Form) */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('drink')
                    ->label('Jenis Minuman')
                    ->options([
                        'kopi' => 'Kopi',
                        'teh'  => 'Teh',
                    ])
                    ->required(),

                TextInput::make('ml')
                    ->label('Volume (ml)')
                    ->numeric()
                    ->required(),

                TextInput::make('price')
                    ->label('Harga (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('drink')->label('Minuman')->sortable()->searchable(),
                TextColumn::make('ml')->label('Volume (ml)')->sortable(),
                TextColumn::make('price')->label('Harga')->money('IDR', true)->sortable(),
                TextColumn::make('updated_at')->dateTime('d M Y H:i')->label('Diperbarui'),
            ])
            ->actions([
                EditAction::make()
                    ->modalWidth('md')
                    ->after(function () {
                        Notification::make()
                            ->title('Harga berhasil diperbarui!')
                            ->success()
                            ->send();
                    }),
                DeleteAction::make(),
                ButtonAction::make('triggerPump')
                    ->label('Start Pompa')
                    ->icon('heroicon-o-bolt')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pengisian')
                    ->modalDescription(
                        fn($record) =>
                        "Apakah Anda yakin ingin melanjutkan pengisian untuk minuman "
                            . ucfirst($record->drink) . " sebanyak {$record->ml} ml?"
                    )
                    ->action(function ($record) {
                        $drink = strtolower($record->drink);
                        $ml = $record->ml;
                        $amount = $record->price;

                        // Create job file for ESP device
                        $file = storage_path("app/device_job_{$drink}.json");
                        $job = [
                            'ml'     => $ml,
                            'drink'  => $drink,
                            'at'     => now()->toIso8601String(),
                        ];
                        file_put_contents($file, json_encode($job));

                        // Generate order_id and code_transactions
                        $orderId = "WADAH-{$drink}-" . now()->format('YmdHis') . "-{$ml}-" . random_int(1000, 9999);
                        $code = 'WADAH' . random_int(10000, 99999);

                        // Create transaction record
                        Transaction::create([
                            'order_id'          => $orderId,
                            'code_transactions' => $code,
                            'transaction_type'  => 'CASH',
                            'drink'             => $drink,
                            'ml'                => $ml,
                            'amount'            => $amount,
                            'status'            => 'success',
                            'issuer'            => 'Manual',
                            'paid_at'           => now(),
                        ]);

                        Notification::make()
                            ->title('Perintah Terkirim')
                            ->body("ESP akan segera memproses pengisian {$drink} dengan {$ml} ml. Transaksi berhasil dicatat.")
                            ->success()
                            ->send();
                    }),
            ])
            ->headerActions([
                CreateAction::make()
                    ->modalWidth('md')
                    ->after(function () {
                        Notification::make()
                            ->title('Harga baru berhasil ditambahkan!')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDrinkPrices::route('/'),
            'create' => Pages\CreateDrinkPrice::route('/create'),
            'edit'   => Pages\EditDrinkPrice::route('/{record}/edit'),
        ];
    }
}
