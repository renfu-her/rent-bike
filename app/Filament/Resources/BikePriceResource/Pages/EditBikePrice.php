<?php

namespace App\Filament\Resources\BikePriceResource\Pages;

use App\Filament\Resources\BikePriceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBikePrice extends EditRecord
{
    protected static string $resource = BikePriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
