<?php

namespace App\Filament\Resources\BikeResource\Pages;

use App\Filament\Resources\BikeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBike extends CreateRecord
{
    protected static string $resource = BikeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['accessory_items_sync'] = $data['accessory_items'] ?? [];
        unset($data['accessory_items']);
        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        $syncData = [];
        foreach ($this->data['accessory_items_sync'] ?? [] as $item) {
            $syncData[$item['accessory_id']] = [
                'qty' => $item['qty'],
                'price' => $item['price'],
            ];
        }
        $record->accessories()->sync($syncData);
    }
}
