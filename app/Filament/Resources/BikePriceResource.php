<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BikePriceResource\Pages;
use App\Filament\Resources\BikePriceResource\RelationManagers;
use App\Models\BikePrice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BikePriceResource extends Resource
{
    protected static ?string $model = BikePrice::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    
    protected static ?string $navigationGroup = '商店管理';
    
    protected static ?string $navigationLabel = '價格管理';
    
    protected static ?string $modelLabel = '價格';
    
    protected static ?string $pluralModelLabel = '價格';
    
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('bike_id')
                    ->label('所屬機車')
                    ->relationship('bike', 'plate_no')
                    ->required(),
                Forms\Components\TextInput::make('rental_days')
                    ->label('租借天數')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Forms\Components\TextInput::make('price_amount')
                    ->label('價格')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('NT$'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bike.plate_no')
                    ->label('所屬機車')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rental_days')
                    ->label('租借天數')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_amount')
                    ->label('價格')
                    ->money('TWD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('建立時間')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新時間')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBikePrices::route('/'),
            'create' => Pages\CreateBikePrice::route('/create'),
            'edit' => Pages\EditBikePrice::route('/{record}/edit'),
        ];
    }
}
