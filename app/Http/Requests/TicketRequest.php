<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'file' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,zip',
        ];

        // Only agents should be validating these specific updates
        if ($this->user()->type->value === 'agent') {
            $rules['priority'] = 'nullable|in:low,medium,high';
            $rules['status'] = 'nullable|in:open,in_progress,resolved,closed';
            $rules['agent_id'] = 'nullable|exists:users,id';
        }

        return $rules;
    }
}
