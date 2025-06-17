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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('姓名')->required(),
                Forms\Components\TextInput::make('email')->label('電子郵件')->email()->required(),
                Forms\Components\TextInput::make('phone')->label('電話')->tel(),
                Forms\Components\TextInput::make('address')->label('地址'),
                Forms\Components\TextInput::make('password')->label('密碼')->password()->required()->dehydrateStateUsing(fn($state) => bcrypt($state)),
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
                Tables\Columns\TextColumn::make('status')->label('狀態'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
