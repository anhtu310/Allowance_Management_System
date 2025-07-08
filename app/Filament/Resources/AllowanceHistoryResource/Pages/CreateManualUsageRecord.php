<?php

namespace App\Filament\Resources\AllowanceHistoryResource\Pages;

use App\Filament\Resources\AllowanceHistoryResource;
use App\Mail\ManualAllowanceUsageMail;
use App\Models\Customer;
use App\Models\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;

class CreateManualUsageRecord extends CreateRecord
{
    protected static string $resource = AllowanceHistoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = 'increase';
        $data['created_by'] = auth()->id();

        // ✅ Tính balance ngay tại đây
        $customer = Customer::find($data['customer_id']);
        $data['balance'] = ($customer?->total_allowance ?? 0) + $data['delta'];

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        $customer = $record->customer;

        // ✅ Cộng vào customer
        $customer->total_allowance += $record->delta;
        $customer->save();

        // 📧 Gửi email
        Mail::to($customer->email)->send(
            new ManualAllowanceUsageMail($record)
        );

        // 📝 Nội dung HTML notification
        $htmlContent = view('emails.manual-allowance-usage', [
            'record' => $record,
        ])->render();

        // 💾 Tạo notification
        $notification = Notification::create([
            'content' => $htmlContent,
        ]);

        // 🔗 Gán vào record
        $record->notifications_id = $notification->id;
        $record->save();
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Manual allowance usage recorded successfully.';
    }

    protected function getRedirectUrl(): string
    {
        return AllowanceHistoryResource::getUrl('index');
    }
}
