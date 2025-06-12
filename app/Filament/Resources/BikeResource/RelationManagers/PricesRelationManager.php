<?php

namespace App\Filament\Resources\BikeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PricesRelationManager extends RelationManager
{
    protected static string $relationship = 'prices';

    protected static ?string $recordTitleAttribute = 'rental_days';

    protected static ?string $title = '價格方案';

    protected static ?string $modelLabel = '價格方案';

    protected static ?string $pluralModelLabel = '價格方案';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
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
                Tables\Columns\TextColumn::make('created_at')
                    ->label('建立時間')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('新增價格方案'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('編輯'),
                Tables\Actions\DeleteAction::make()
                    ->label('刪除'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('刪除所選'),
                ]),
            ]);
    }
} 