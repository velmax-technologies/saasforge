<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'invited_by',
        'role_id',
        'email',
        'token_hash',
        'status',
        'expires_at',
        'accepted_at',
        'revoked_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'accepted_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(
            Organization::class,
            'organization_id'
        );
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'invited_by'
        );
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(
            Role::class,
            'role_id'
        );
    }

    public function isPending(): bool
    {
        return $this->status === 'pending'
            && ! $this->isExpired();
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}