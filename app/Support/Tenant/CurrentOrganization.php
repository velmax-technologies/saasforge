<?php

namespace App\Support\Tenant;

use App\Models\Organization;
use App\Models\User;
use RuntimeException;

class CurrentOrganization
{
    protected ?Organization $organization = null;

    public function set(Organization $organization): void
    {
        $this->organization = $organization;
    }

    public function setForUser(User $user, Organization $organization): void
    {
        if (! $this->userCanAccess($user, $organization)) {
            throw new RuntimeException(
                'User does not have access to this organization.'
            );
        }

        $this->set($organization);
    }

    public function get(): Organization
    {
        if (! $this->organization) {
            throw new RuntimeException(
                'No organization has been resolved for the current request.'
            );
        }

        return $this->organization;
    }

    public function id(): int
    {
        return $this->get()->id;
    }

    public function check(): bool
    {
        return $this->organization !== null;
    }

    public function clear(): void
    {
        $this->organization = null;
    }

    public function userCanAccess(
        User $user,
        Organization $organization
    ): bool {
        return $user->organizations()
            ->where('organizations.id', $organization->id)
            ->wherePivot('status', 'active')
            ->exists();
    }
}