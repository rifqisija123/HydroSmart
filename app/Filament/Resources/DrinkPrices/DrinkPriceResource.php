<?php

namespace App\Filament\Resources\DrinkPrices;

use App\Models\DrinkPrice;
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
