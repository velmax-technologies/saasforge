<?php

namespace App\Models;

use App\Enums\OrganizationRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Organization extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'email',
        'phone',
        'logo',
        'timezone',
        'locale',
        'settings',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Organization $organization) {
            $organization->uuid ??= (string) Str::uuid();

            if (empty($organization->slug)) {
                $organization->slug = Str::slug($organization->name);
            }
        });
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
        User::class,
        'organization_user',
        'organization_id',
        'user_id'
        )->withPivot([
            'role',
            'role_id',
            'status',
            'joined_at',
        ])->withTimestamps();
    }

    public function owners(): BelongsToMany
    {
        return $this->users()
            ->wherePivot('role', OrganizationRole::OWNER->value);
    }
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class, 'organization_id');
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(
            OrganizationInvitation::class
        );
    }
}