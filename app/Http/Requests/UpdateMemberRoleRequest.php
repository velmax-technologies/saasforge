<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMemberRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission(
            'members.manage',
            $this->route('organization')
        );
    }

    public function rules(): array
    {
        return [
            'role_id' => [
                'required',
                'integer',
                Rule::exists('roles', 'id'),
            ],
        ];
    }
}