<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BikeResource\Pages;
use App\Filament\Resources\BikeResource\RelationManagers;
use App\Models\Bike;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BikeResource extends Resource
{
    protected static ?string $model = Bike::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    
    protected static ?string $navigationGroup = '商店管理';
    
    protected static ?string $navigationLabel = '機車管理';
    
    protected static ?string $modelLabel = '機車';
    
    protected static ?string $pluralModelLabel = '機車';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('store_id')
                    ->label('所屬商店')
                    ->relationship('store', 'name')
                    ->required(),
                Forms\Components\TextInput::make('plate_no')
                    ->label('車牌號碼')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('model')
                    ->label('機車型號')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->label('狀態')
                    ->options([
                        'available' => '待出租',
                        'rented' => '已出租',
                        'maintenance' => '維修中',
                        'disabled' => '停用',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('store.name')
                    ->label('所屬商店')
                    ->searchable(),
                Tables\Columns\TextColumn::make('plate_no')
                    ->label('車牌號碼')
                    ->searchable(),
                Tables\Columns\TextColumn::make('model')
                    ->label('機車型號')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->label('狀態')
                    ->options([
                        'available' => '待出租',
                        'rented' => '已出租',
                        'maintenance' => '維修中',
                        'disabled' => '停用',
                    ]),
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
                Tables\Filters\SelectFilter::make('status')
                    ->label('狀態')
                    ->options([
                        'available' => '待出租',
                        'rented' => '已出租',
                        'maintenance' => '維修中',
                        'disabled' => '停用',
                    ]),
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
            RelationManagers\PricesRelationManager::class,
            RelationManagers\AccessoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBikes::route('/'),
            'create' => Pages\CreateBike::route('/create'),
            'edit' => Pages\EditBike::route('/{record}/edit'),
        ];
    }
}
