<?php

namespace App\Models\Concerns;

use App\Models\Organization;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRoles
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'organization_user',
            'user_id',
            'role_id'
        )->withPivot([
            'organization_id',
            'status',
            'joined_at',
        ])->withTimestamps();
    }

    public function hasRole(
        string $role,
        ?Organization $organization = null
    ): bool {
        $query = $this->memberships()
            ->whereHas('assignedRole', function ($query) use ($role) {
                $query->where('slug', $role);
            })
            ->where('status', 'active');

        if ($organization) {
            $query->where(
                'organization_id',
                $organization->id
            );
        }

        return $query->exists();
    }

    public function hasPermission(
        string $permission,
        ?Organization $organization = null
    ): bool {
        $query = $this->memberships()
            ->where('status', 'active')
            ->whereHas('assignedRole.permissions', function ($query) use ($permission) {
                $query->where('slug', $permission);
            });

        if ($organization) {
            $query->where(
                'organization_id',
                $organization->id
            );
        }

        return $query->exists();
    }
}
