<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    
    protected static ?string $navigationGroup = '租借管理';
    
    protected static ?string $navigationLabel = '罰單管理';
    
    protected static ?string $modelLabel = '罰單';
    
    protected static ?string $pluralModelLabel = '罰單';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('bike_id')
                    ->label('機車')
                    ->relationship('bike', 'plate_no')
                    ->required(),
                Forms\Components\DateTimePicker::make('issued_time')
                    ->label('開立時間')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('罰款金額')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('NT$'),
                Forms\Components\Toggle::make('is_resolved')
                    ->label('是否處理完成')
                    ->required()
                    ->default(false),
                Forms\Components\Select::make('related_order_id')
                    ->label('關聯訂單')
                    ->relationship('relatedOrder', 'id')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bike.plate_no')
                    ->label('機車')
                    ->searchable(),
                Tables\Columns\TextColumn::make('issued_time')
                    ->label('開立時間')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('罰款金額')
                    ->money('TWD')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_resolved')
                    ->label('是否處理完成')
                    ->boolean(),
                Tables\Columns\TextColumn::make('relatedOrder.id')
                    ->label('關聯訂單')
                    ->searchable(),
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
                Tables\Filters\TernaryFilter::make('is_resolved')
                    ->label('處理狀態')
                    ->placeholder('全部')
                    ->trueLabel('已處理')
                    ->falseLabel('未處理'),
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
