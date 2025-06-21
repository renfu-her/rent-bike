<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = '會員管理';
    protected static ?string $navigationLabel = '會員管理';
    protected static ?string $modelLabel = '會員';
    protected static ?string $pluralModelLabel = '會員';
    protected static ?int $navigationSort = 10;

    // 隱藏新增會員按鈕
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('姓名')->required(),
                Forms\Components\TextInput::make('email')->label('電子郵件')->email()->required()->disabledOn('edit'),
                Forms\Components\TextInput::make('phone')->label('電話')->tel(),
                Forms\Components\TextInput::make('address')->label('地址'),
                Forms\Components\TextInput::make('id_number')
                    ->label('身份證字號')
                    ->extraAttributes(['oninput' => 'this.value = this.value.toUpperCase()'])
                    ->dehydrateStateUsing(fn($state) => strtoupper($state)),
                Forms\Components\Select::make('gender')->label('性別')->options(['男'=>'男','女'=>'女']),
                Forms\Components\TextInput::make('password')
                    ->label('密碼')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                    ->required(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                    ->nullable()
                    ->hiddenOn('view')
                    ->dehydrated(fn($state) => filled($state)),
                Forms\Components\Select::make('status')->label('狀態')->options([
                    1 => '啟用',
                    0 => '停用',
                ])->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('姓名'),
                Tables\Columns\TextColumn::make('email')->label('電子郵件'),
                Tables\Columns\TextColumn::make('phone')->label('電話'),
                Tables\Columns\TextColumn::make('id_number')->label('身份證字號'),
                Tables\Columns\TextColumn::make('gender')->label('性別'),
                Tables\Columns\IconColumn::make('status')
                    ->label('狀態')
                    ->boolean(fn($state) => $state == 1)
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('secondary'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListMembers::route('/'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
