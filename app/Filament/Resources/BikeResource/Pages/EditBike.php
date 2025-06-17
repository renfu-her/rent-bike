<?php

namespace App\Filament\Resources\BikeResource\Pages;

use App\Filament\Resources\BikeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBike extends EditRecord
{
    protected static string $resource = BikeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['accessory_items_sync'] = $data['accessory_items'] ?? [];
        unset($data['accessory_items']);
        return $data;
    }

    protected function afterSave($record, array $data): void
    {
        $syncData = [];
        foreach ($data['accessory_items_sync'] ?? [] as $item) {
            $syncData[$item['accessory_id']] = [
                'qty' => $item['qty'],
                'price' => $item['price'],
            ];
        }
        $record->accessories()->sync($syncData);
    }
}
