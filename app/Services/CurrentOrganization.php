<?php

namespace App\Services;

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

    public function get(): Organization
    {
        if (! $this->organization) {
            throw new RuntimeException(
                "No current organization has been selected."
            );
        }

        return $this->organization;
    }

    public function id(): int
    {
        return $this->get()->id;
    }

    public function has(): bool
    {
        return $this->organization !== null;
    }

    public function clear(): void
    {
        $this->organization = null;
    }

    public function forUser(User $user): ?Organization
    {
        return $user->organizations()
            ->wherePivot('status', 'active')
            ->orderBy('organizations.id')
            ->first();
    }

    public function setForUser(User $user): ?Organization
    {
        $organization = $this->forUser($user);

        if ($organization) {
            $this->set($organization);
        }

        return $organization;
    }
}
