<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncGameAnalyticsRequest extends FormRequest
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
            'events' => ['required', 'array', 'min:1', 'max:500'],
            'events.*.client_event_id' => ['required', 'string', 'max:191'],
            'events.*.game_key' => ['required', 'string', 'max:120'],
            'events.*.event_type' => ['required', 'string', 'max:120'],
            'events.*.status' => ['nullable', 'string', 'max:50'],
            'events.*.attempts' => ['nullable', 'integer', 'min:0', 'max:255'],
            'events.*.word_length' => ['nullable', 'integer', 'min:1', 'max:50'],
            'events.*.score' => ['nullable', 'integer', 'min:0', 'max:1000000'],
            'events.*.duration_seconds' => ['nullable', 'integer', 'min:0', 'max:86400'],
            'events.*.occurred_at' => ['nullable', 'date'],
            'events.*.metadata' => ['nullable', 'array'],
        ];
    }
}
