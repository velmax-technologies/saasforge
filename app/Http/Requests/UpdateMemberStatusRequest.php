<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMemberStatusRequest extends FormRequest
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
            'status' => [
                'required',
                Rule::in([
                    'active',
                    'suspended',
                ]),
            ],
        ];
    }
}