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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
                Forms\Components\Section::make('罰單主要資訊')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('ticket_number')
                            ->label('罰單編號')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('bike_id')
                            ->label('機車車牌')
                            ->relationship('bike', 'plate_no')
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('related_order_id')
                            ->label('關聯訂單編號')
                            ->relationship('relatedOrder', 'order_number')
                            ->searchable(),
                        Forms\Components\DateTimePicker::make('issued_time')
                            ->label('違規時間')
                            ->required(),
                        Forms\Components\DatePicker::make('due_date')
                            ->label('應到案日期'),
                        Forms\Components\TextInput::make('violation_location')
                            ->label('違規地點')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('violation_description')
                            ->label('違規事實')
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('罰鍰人資訊')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('fined_person_name')
                            ->label('罰鍰人姓名'),
                        Forms\Components\TextInput::make('fined_person_id_number')
                            ->label('罰鍰人身分證號'),
                    ]),
                Forms\Components\Section::make('罰單管理')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->label('罰款金額')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->prefix('NT$'),
                        Forms\Components\FileUpload::make('image')
                            ->label('罰單圖片')
                            ->image()
                            ->imageEditor()
                            ->directory('tickets')
                            ->downloadable()
                            ->openable()
                            ->getUploadedFileNameForStorageUsing(fn($file): string => (string) str(Str::uuid7() . '.webp'))
                            ->saveUploadedFileUsing(function ($file) {
                                $manager = new ImageManager(new Driver());
                                $image = $manager->read($file);
                                $image->cover(600, 800);
                                $filename = Str::uuid7()->toString() . '.webp';
                                if (!file_exists(storage_path('app/public/tickets'))) {
                                    mkdir(storage_path('app/public/tickets'), 0755, true);
                                }
                                $image->toWebp(80)->save(storage_path('app/public/tickets/' . $filename));
                                return 'tickets/' . $filename;
                            })
                            ->deleteUploadedFileUsing(fn($file) => $file ? Storage::disk('public')->delete($file) : null)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('issuer_name')
                            ->label('填單人'),
                        Forms\Components\TextInput::make('issuing_authority')
                            ->label('舉發單位'),
                        Forms\Components\Toggle::make('is_resolved')
                            ->label('是否處理完成')
                            ->default(false),
                        Forms\Components\Radio::make('status')
                            ->label('寄送狀態')
                            ->options(['unsent' => '罰單未寄送', 'sent' => '罰單已寄送'])
                            ->default('unsent')
                            ->required(),
                        Forms\Components\Select::make('handler_id')
                            ->label('處理者')
                            ->relationship('handler', 'name')
                            ->searchable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label('圖片')->square()->defaultImageUrl(url('/images/fa-image.svg')),
                Tables\Columns\TextColumn::make('ticket_number')->label('罰單編號')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('bike.plate_no')->label('車牌')->searchable(),
                Tables\Columns\TextColumn::make('violation_location')->label('違規地點')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('issued_time')->label('違規時間')->dateTime('Y-m-d')->sortable(),
                Tables\Columns\TextColumn::make('amount')->label('罰款')->money('TWD')->sortable(),
                Tables\Columns\IconColumn::make('is_resolved')->label('已處理')->boolean(),
                Tables\Columns\TextColumn::make('status')->label('寄送狀態')->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'unsent' => 'warning',
                        'sent' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'unsent' => '未寄送',
                        'sent' => '已寄送',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('handler.name')->label('處理者')->searchable()->default('未指派'),
                Tables\Columns\TextColumn::make('relatedOrder.order_number')->label('關聯訂單')->searchable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_resolved')->label('處理狀態')->placeholder('全部')->trueLabel('已處理')->falseLabel('未處理'),
                Tables\Filters\SelectFilter::make('status')->label('寄送狀態')->options(['unsent' => '未寄送', 'sent' => '已寄送']),
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
