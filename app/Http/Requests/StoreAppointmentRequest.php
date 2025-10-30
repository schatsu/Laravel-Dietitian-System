<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string', 'max:20'],
            'note' => ['nullable', 'string'],
            'appointment_slot_id' => [
                'required',
                Rule::exists('appointment_slots', 'id')->where(function ($query) {
                    $query->where('is_active', true)->where('is_booked', false);
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Ad soyad alanı zorunludur.',
            'email.required' => 'E-posta adresi zorunludur.',
            'email.email' => 'Geçerli bir e-posta adresi girin.',
            'phone.required' => 'Telefon numarası zorunludur.',
            'appointment_slot_id.required' => 'Lütfen bir saat seçin.',
            'appointment_slot_id.exists' => 'Seçilen saat geçerli değil veya dolu.',
        ];
    }
}
