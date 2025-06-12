<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    
    protected static ?string $navigationGroup = '租借管理';
    
    protected static ?string $navigationLabel = '訂單管理';
    
    protected static ?string $modelLabel = '訂單';
    
    protected static ?string $pluralModelLabel = '訂單';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('bike_id')
                    ->label('租借機車')
                    ->relationship('bike', 'plate_no')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('租借者')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\DateTimePicker::make('start_time')
                    ->label('租借開始時間')
                    ->required(),
                Forms\Components\DateTimePicker::make('end_time')
                    ->label('租借結束時間')
                    ->nullable(),
                Forms\Components\Select::make('status')
                    ->label('狀態')
                    ->options([
                        'active' => '進行中',
                        'completed' => '已完成',
                        'cancelled' => '已取消',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('total_price')
                    ->label('總金額')
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
                    ->label('租借機車')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('租借者')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('租借開始時間')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('租借結束時間')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->label('狀態')
                    ->options([
                        'active' => '進行中',
                        'completed' => '已完成',
                        'cancelled' => '已取消',
                    ]),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('總金額')
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
                Tables\Filters\SelectFilter::make('status')
                    ->label('狀態')
                    ->options([
                        'active' => '進行中',
                        'completed' => '已完成',
                        'cancelled' => '已取消',
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
