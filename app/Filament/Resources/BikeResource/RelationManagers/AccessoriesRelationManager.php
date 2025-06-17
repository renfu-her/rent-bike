<?php

namespace App\Filament\Resources\BikeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Accessory;

class AccessoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'accessoryBikes';

    protected static ?string $title = '配件';
    protected static ?string $modelLabel = '配件';
    protected static ?string $pluralModelLabel = '配件';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('accessory_id')
                    ->label('配件')
                    ->options(fn () => Accessory::all()->pluck('name', 'accessory_id')->toArray())
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('狀態')
                    ->options([
                        1 => '啟用',
                        0 => '停用',
                    ])
                    ->default(1)
                    ->required(),
                Forms\Components\TextInput::make('qty')
                    ->label('數量')
                    ->numeric()
                    ->default(1)
                    ->required()
                    ->visible(fn ($get) => $get('status') == 1),
                Forms\Components\TextInput::make('price')
                    ->label('價格')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->visible(fn ($get) => $get('status') == 1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('accessory.name')
                    ->label('配件')
                    ->searchable(),
                Tables\Columns\TextColumn::make('qty')
                    ->label('數量')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('價格')
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('狀態')
                    ->boolean()
                    ->trueIcon('heroicon-o-check')
                    ->falseIcon('heroicon-o-x-mark'),
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
                    ->label('新增配件'),
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