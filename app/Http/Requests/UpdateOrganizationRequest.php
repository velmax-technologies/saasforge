<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $organization = $this->route('organization');

        return $this->user()
            ->hasPermission('organization.manage', $organization);
    }

    public function rules(): array
    {
        $organization = $this->route('organization');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('organizations', 'slug')
                    ->ignore($organization?->id),
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'timezone' => [
                'required',
                'timezone',
            ],

            'locale' => [
                'required',
                'string',
                'max:10',
            ],
        ];
    }
}