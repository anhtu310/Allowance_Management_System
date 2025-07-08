<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class)->with('permissions');
    }

    public function handledRequests(): HasMany
    {
        return $this->hasMany(AllowanceRequest::class, 'handled_by');
    }
    public function hasPermission(string $permission): bool
    {
        return $this->role?->permissions->contains('name', $permission);
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role?->name, $roles);
    }

}
