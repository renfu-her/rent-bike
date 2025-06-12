<?php

namespace App\Filament\Resources\BikeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AccessoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'accessory';

    protected static ?string $recordTitleAttribute = 'helmet_count';

    protected static ?string $title = '配件';

    protected static ?string $modelLabel = '配件';

    protected static ?string $pluralModelLabel = '配件';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('helmet_count')
                    ->label('安全帽數量')
                    ->required()
                    ->numeric()
                    ->default(2)
                    ->minValue(0),
                Forms\Components\Toggle::make('has_lock')
                    ->label('附鎖')
                    ->required()
                    ->default(true),
                Forms\Components\Toggle::make('has_toolkit')
                    ->label('附維修工具')
                    ->required()
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('helmet_count')
                    ->label('安全帽數量')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('has_lock')
                    ->label('附鎖')
                    ->boolean(),
                Tables\Columns\IconColumn::make('has_toolkit')
                    ->label('附維修工具')
                    ->boolean(),
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