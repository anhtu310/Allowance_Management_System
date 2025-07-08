<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Notification extends Model
{
    public $timestamps = false;
    protected $fillable = ['content'];

    public function history(): HasOne
    {
        return $this->hasOne(AllowanceHistory::class, 'notifications_id');
    }

    public function getCustomerNameAttribute(): ?string
    {
        return $this->history?->customer?->name;
    }
}
