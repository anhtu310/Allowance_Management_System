<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AllowanceRequest extends Model
{
    protected $fillable = [
        'customer_id',
        'amount_requested',
        'reason',
        'status',
        'handled_by',
            'handled_at',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function histories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AllowanceHistory::class, 'request_id');
    }

    public function latestHistory(): ?\App\Models\AllowanceHistory
    {
        return $this->histories()->latest('id')->first();
    }

}

