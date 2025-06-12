<?php

namespace App\Filament\Resources\BikePriceResource\Pages;

use App\Filament\Resources\BikePriceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBikePrices extends ListRecords
{
    protected static string $resource = BikePriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
