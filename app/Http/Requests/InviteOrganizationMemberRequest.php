<?php

namespace App\Http\Requests;

use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InviteOrganizationMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        $organization = $this->route('organization');

        return $organization instanceof Organization
            && $this->user()->hasPermission(
                'members.manage',
                $organization
            );
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email:rfc',
                'max:255',
            ],
            'role_id' => [
                'required',
                'integer',
                Rule::exists('roles', 'id'),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower(trim((string) $this->email)),
        ]);
    }
}