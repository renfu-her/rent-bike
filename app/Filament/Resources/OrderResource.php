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
                Forms\Components\TextInput::make('order_number')
                    ->label('訂單編號')
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\Select::make('bike_id')
                    ->label('租借機車')
                    ->relationship('bike', 'plate_no')
                    ->required(),
                Forms\Components\Select::make('member_id')
                    ->label('租借會員')
                    ->relationship('member', 'name')
                    ->required(),
                Forms\Components\TextInput::make('rental_plan')
                    ->label('租賃方案')
                    ->required(),
                Forms\Components\DatePicker::make('booking_date')
                    ->label('預約日期')
                    ->required(),
                Forms\Components\DateTimePicker::make('start_time')
                    ->label('租借開始時間')
                    ->nullable(),
                Forms\Components\DateTimePicker::make('end_time')
                    ->label('租借結束時間')
                    ->nullable()
                    ->helperText('設定結束時間後，如果訂單已完成或取消，機車狀態會自動改為待出租'),
                Forms\Components\Select::make('status')
                    ->label('狀態')
                    ->options([
                        'pending' => '待處理',
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
                Tables\Columns\TextColumn::make('order_number')
                    ->label('訂單編號')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bike.plate_no')
                    ->label('租借機車')
                    ->searchable(),
                Tables\Columns\TextColumn::make('member.name')
                    ->label('租借會員')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rental_plan')
                    ->label('租賃方案'),
                Tables\Columns\TextColumn::make('booking_date')
                    ->label('預約日期')
                    ->dateTime('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('租借開始時間')
                    ->dateTime('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('租借結束時間')
                    ->dateTime('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('總金額')
                    ->money('TWD')
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->label('狀態')
                    ->options([
                        'pending' => '待處理',
                        'active' => '進行中',
                        'completed' => '已完成',
                        'cancelled' => '已取消',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('建立時間')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('狀態')
                    ->options([
                        'pending' => '待處理',
                        'active' => '進行中',
                        'completed' => '已完成',
                        'cancelled' => '已取消',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('changeStatus')
                    ->label('變更狀態')
                    ->icon('heroicon-o-pencil-square')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('訂單狀態')
                            ->options([
                                'pending' => '待處理',
                                'active' => '進行中',
                                'completed' => '已完成',
                                'cancelled' => '已取消',
                            ])
                            ->default(fn (Order $record): string => $record->status)
                            ->required(),
                        Forms\Components\DateTimePicker::make('end_time')
                            ->label('結束時間')
                            ->default(fn (Order $record): ?string => $record->end_time?->format('Y-m-d H:i:s'))
                            ->helperText('設定結束時間後，機車狀態會自動改為待出租'),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $record->update($data);
                        
                        // 更新機車狀態
                        $bike = $record->bike;
                        if ($bike) {
                            switch ($data['status']) {
                                case 'completed':
                                    $bike->update(['status' => 'rented']);
                                    break;
                                    
                                case 'cancelled':
                                    $bike->update(['status' => 'available']);
                                    break;
                                    
                                case 'active':
                                    $bike->update(['status' => 'rented']);
                                    break;
                                    
                                case 'pending':
                                    $bike->update(['status' => 'pending']);
                                    break;
                            }
                            
                            // 如果結束時間已設定，且訂單已完成或取消，則機車狀態改為待出租
                            if (isset($data['end_time']) && $data['end_time'] && in_array($data['status'], ['completed', 'cancelled'])) {
                                $bike->update(['status' => 'available']);
                            }
                        }
                    }),
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
            // 'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
