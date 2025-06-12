<?php

namespace App\Filament\Resources\BikePriceResource\Pages;

use App\Filament\Resources\BikePriceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBikePrice extends CreateRecord
{
    protected static string $resource = BikePriceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
