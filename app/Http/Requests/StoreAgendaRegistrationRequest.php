<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAgendaRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return self::agendaRegistrationRules();
    }

    public static function agendaRegistrationRules(): array
    {
        return [
            'parent_name' => ['required', 'string', 'min:3', 'max:150'],
            'child_name' => ['nullable', 'string', 'max:150'],
            'child_age' => ['nullable', 'integer', 'min:1', 'max:18'],
            'whatsapp_number' => ['required', 'regex:/^(\\+62|62|0)8[0-9]{8,13}$/', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'participant_count' => ['required', 'integer', 'min:1', 'max:20'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'whatsapp_number.regex' => 'Nomor WhatsApp harus memakai format Indonesia yang valid.',
        ];
    }
}
