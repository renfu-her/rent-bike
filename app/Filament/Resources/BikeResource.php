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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
                Forms\Components\Select::make('store_store_id')
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
                Forms\Components\FileUpload::make('image')
                    ->label('機車圖片')
                    ->image()
                    ->imageEditor()
                    ->directory('bikes')
                    ->columnSpanFull()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->downloadable()
                    ->openable()
                    ->getUploadedFileNameForStorageUsing(
                        fn($file): string => (string) str(Str::uuid7() . '.webp')
                    )
                    ->saveUploadedFileUsing(function ($file) {
                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($file);
                        $image->cover(800, 450);
                        $filename = Str::uuid7()->toString() . '.webp';

                        if (!file_exists(storage_path('app/public/bikes'))) {
                            mkdir(storage_path('app/public/bikes'), 0755, true);
                        }

                        $image->toWebp(80)->save(storage_path('app/public/bikes/' . $filename));
                        return 'bikes/' . $filename;
                    })
                    ->deleteUploadedFileUsing(function ($file) {
                        if ($file) {
                            Storage::disk('public')->delete($file);
                        }
                    }),
                Forms\Components\Select::make('status')
                    ->label('狀態')
                    ->options([
                        'available' => '待出租',
                        'rented' => '已出租',
                        'maintenance' => '維修中',
                        'disabled' => '停用',
                    ])
                    ->required(),
                Forms\Components\Group::make()
                    ->label('配件')
                    ->schema([
                        Forms\Components\Repeater::make('accessories')
                            ->label('配件列表')
                            ->schema([
                                Forms\Components\TextInput::make('name')->label('配件名稱')->required(),
                                Forms\Components\TextInput::make('qty')->label('數量')->numeric()->default(0),
                                Forms\Components\TextInput::make('price')->label('費用')->numeric()->default(0),
                                Forms\Components\Toggle::make('enabled')->label('啟用')->default(false),
                            ])
                            ->default([
                                ['name' => '安全帽', 'qty' => 2, 'price' => 0, 'enabled' => true],
                                ['name' => '帽套', 'qty' => 2, 'price' => 0, 'enabled' => false],
                                ['name' => '手機架', 'qty' => 1, 'price' => 0, 'enabled' => false],
                                ['name' => '電池', 'qty' => 0, 'price' => 0, 'enabled' => false],
                                ['name' => '雨衣', 'qty' => 2, 'price' => 50, 'enabled' => false],
                            ])
                            ->columns(4)
                            ->minItems(1)
                            ->maxItems(10)
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('機車圖片')
                    ->square()
                    ->defaultImageUrl(url('/images/placeholder.png')),
                Tables\Columns\TextColumn::make('store.name')
                    ->label('所屬商店')
                    ->searchable(),
                Tables\Columns\TextColumn::make('plate_no')
                    ->label('車牌號碼')
                    ->searchable(),
                Tables\Columns\TextColumn::make('model')
                    ->label('機車型號')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('狀態')
                    ->formatStateUsing(fn (string $state): string => 
                        match($state) {
                            'available' => '待出租',
                            'rented' => '已出租',
                            'maintenance' => '維修中',
                            'disabled' => '停用',
                            default => $state,
                        }
                    )
                    ->badge()
                    ->color(fn (string $state): string => 
                        match($state) {
                            'available' => 'success',
                            'rented' => 'warning',
                            'maintenance' => 'danger',
                            'disabled' => 'gray',
                            default => 'gray',
                        }
                    ),
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
                Tables\Actions\Action::make('changeStatus')
                    ->label('修改狀態')
                    ->icon('heroicon-o-pencil-square')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('狀態')
                            ->options([
                                'available' => '待出租',
                                'rented' => '已出租',
                                'maintenance' => '維修中',
                                'disabled' => '停用',
                            ])
                            ->default(fn (Bike $record): string => $record->status)
                            ->required(),
                    ])
                    ->action(function (Bike $record, array $data): void {
                        $record->update($data);
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
            RelationManagers\PricesRelationManager::class,
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
