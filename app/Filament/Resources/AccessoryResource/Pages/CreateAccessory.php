<?php

namespace App\Filament\Resources\AccessoryResource\Pages;

use App\Filament\Resources\AccessoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAccessory extends CreateRecord
{
    protected static string $resource = AccessoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
