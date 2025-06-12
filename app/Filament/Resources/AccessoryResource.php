<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccessoryResource\Pages;
use App\Filament\Resources\AccessoryResource\RelationManagers;
use App\Models\Accessory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccessoryResource extends Resource
{
    protected static ?string $model = Accessory::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    
    protected static ?string $navigationGroup = '商店管理';
    
    protected static ?string $navigationLabel = '配件管理';
    
    protected static ?string $modelLabel = '配件';
    
    protected static ?string $pluralModelLabel = '配件';
    
    protected static ?int $navigationSort = 3;

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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bike.plate_no')
                    ->label('所屬機車')
                    ->searchable(),
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
            'index' => Pages\ListAccessories::route('/'),
            'create' => Pages\CreateAccessory::route('/create'),
            'edit' => Pages\EditAccessory::route('/{record}/edit'),
        ];
    }
}
