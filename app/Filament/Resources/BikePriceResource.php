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

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

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
                Forms\Components\Select::make('price_type')
                    ->label('價格類型')
                    ->options([
                        'fixed' => '固定金額',
                        'discount' => '折扣',
                    ])
                    ->required()
                    ->default('fixed')
                    ->live(),
                Forms\Components\TextInput::make('original_price')
                    ->label('原價')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('NT$')
                    ->visible(fn (Forms\Get $get): bool => 
                        $get('price_type') === 'discount'
                    ),
                Forms\Components\TextInput::make('price_amount')
                    ->label('價格')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->prefix(fn (Forms\Get $get): string => 
                        $get('price_type') === 'discount' ? '' : 'NT$'
                    )
                    ->suffix(fn (Forms\Get $get): string => 
                        $get('price_type') === 'discount' ? '折' : ''
                    )
                    ->helperText(fn (Forms\Get $get): string => 
                        $get('price_type') === 'discount' ? '例如：95 代表 9.5 折' : ''
                    )
                    ->visible(fn (Forms\Get $get): bool => 
                        $get('price_type') !== null
                    ),
                Forms\Components\FileUpload::make('image')
                    ->label('圖片')
                    ->image()
                    ->imageEditor()
                    ->directory('bike_prices')
                    ->columnSpanFull()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->downloadable()
                    ->openable()
                    ->nullable(),
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
                Tables\Columns\TextColumn::make('price_type')
                    ->label('價格類型')
                    ->formatStateUsing(fn ($state) => 
                        match($state) {
                            'fixed' => '固定金額',
                            'discount' => '折扣',
                            default => $state,
                        }
                    ),
                Tables\Columns\TextColumn::make('original_price')
                    ->label('原價')
                    ->money('TWD')
                    ->visible(fn ($record) => $record && $record->price_type === 'discount')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_amount')
                    ->label('價格')
                    ->formatStateUsing(function ($record) {
                        if (!$record) return '';
                        if ($record->price_type === 'discount') {
                            return $record->price_amount . '折';
                        }
                        return 'NT$ ' . number_format($record->price_amount);
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('final_price')
                    ->label('最終價格')
                    ->money('TWD')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image')
                    ->label('圖片')
                    ->defaultImageUrl('https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/svgs/solid/image.svg')
                    ->circular(false),
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
