<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Zap\Enums\ScheduleTypes;
use Zap\Models\Schedule;

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
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'string'],
            'end_time' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Ad soyad alanı zorunludur.',
            'email.required' => 'E-posta adresi zorunludur.',
            'email.email' => 'Geçerli bir e-posta adresi girin.',
            'phone.required' => 'Telefon numarası zorunludur.',
            'date.required' => 'Lütfen bir tarih seçin.',
            'date.after_or_equal' => 'Geçmiş bir tarih seçilemez.',
            'start_time.required' => 'Lütfen bir saat seçin.',
            'end_time.required' => 'Lütfen bir saat seçin.',
        ];
    }

    /**
     * Configure the validator instance to check for duplicate appointments.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $date = $this->input('date');
            $startTime = $this->input('start_time');
            $endTime = $this->input('end_time');

            if (!$date || !$startTime || !$endTime) {
                return;
            }

            // Diyetisyeni al (super_admin rolündeki kullanıcı)
            $dietitian = User::role('super_admin')->first();

            if (!$dietitian) {
                return;
            }

            // Aynı tarih ve saat aralığında randevu var mı kontrol et
            $existingAppointment = Schedule::query()
                ->where('schedulable_type', User::class)
                ->where('schedulable_id', $dietitian->id)
                ->where('schedule_type', ScheduleTypes::APPOINTMENT)
                ->whereHas('periods', function ($query) use ($date, $startTime, $endTime) {
                    $query->where('date', $date)
                        ->where('start_time', $startTime)
                        ->where('end_time', $endTime);
                })
                ->exists();

            if ($existingAppointment) {
                $validator->errors()->add(
                    'start_time',
                    'Bu tarih ve saat için zaten bir randevu mevcut. Lütfen başka bir saat seçin.'
                );
            }
        });
    }
}

