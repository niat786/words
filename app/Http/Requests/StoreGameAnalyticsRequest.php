<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGameAnalyticsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_event_id' => ['required', 'string', 'max:191'],
            'game_key' => ['required', 'string', 'max:120'],
            'event_type' => ['required', 'string', 'max:120'],
            'status' => ['nullable', 'string', 'max:50'],
            'attempts' => ['nullable', 'integer', 'min:0', 'max:255'],
            'word_length' => ['nullable', 'integer', 'min:1', 'max:50'],
            'score' => ['nullable', 'integer', 'min:0', 'max:1000000'],
            'duration_seconds' => ['nullable', 'integer', 'min:0', 'max:86400'],
            'occurred_at' => ['nullable', 'date'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
