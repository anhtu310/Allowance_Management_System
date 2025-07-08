<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AllowanceHistory extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'balance', 'delta', 'type', 'description',
        'request_id', 'customer_id', 'notifications_id', 'vouchers_id'
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(AllowanceRequest::class, 'request_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class, 'notifications_id');
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class, 'vouchers_id');
    }

}
