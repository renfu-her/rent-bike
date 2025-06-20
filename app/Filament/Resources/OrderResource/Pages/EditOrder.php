<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Bike;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

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

    protected function afterSave(): void
    {
        $order = $this->record;
        $bike = $order->bike;

        if (!$bike) {
            return;
        }

        // 根據訂單狀態更新機車狀態
        switch ($order->status) {
            case 'completed':
                // 訂單完成時，機車狀態改為已出租
                $bike->update(['status' => 'rented']);
                break;
                
            case 'cancelled':
                // 訂單取消時，機車狀態改為待出租
                $bike->update(['status' => 'available']);
                break;
                
            case 'active':
                // 訂單進行中時，機車狀態改為已出租
                $bike->update(['status' => 'rented']);
                break;
                
            case 'pending':
                // 訂單待處理時，機車狀態改為預約中
                $bike->update(['status' => 'pending']);
                break;
        }

        // 如果結束時間已設定，且訂單已完成或取消，則機車狀態改為待出租
        if ($order->end_time && in_array($order->status, ['completed', 'cancelled'])) {
            $bike->update(['status' => 'available']);
        }
    }
}
