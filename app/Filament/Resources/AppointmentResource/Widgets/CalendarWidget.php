<?php

namespace App\Filament\Resources\AppointmentResource\Widgets;

use App\Enums\AppointmentStatusEnum;
use App\Models\User;
use App\Services\BookAppointmentService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Saade\FilamentFullCalendar\Actions\ViewAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Zap\Models\Schedule;

class CalendarWidget extends FullCalendarWidget
{
    public string|null|Model $model = Schedule::class;

    public function fetchEvents(array $fetchInfo): array
    {
        $dietitian = User::role('super_admin')->first();
        if (!$dietitian) {
            return [];
        }

        $events = [];

        // Randevuları getir (mavi)
        $appointments = Schedule::where('schedulable_type', User::class)
            ->where('schedulable_id', $dietitian->id)
            ->where('schedule_type', \Zap\Enums\ScheduleTypes::APPOINTMENT)
            ->with('periods')
            ->get();

        foreach ($appointments as $appointment) {
            foreach ($appointment->periods as $period) {
                $metadata = $appointment->metadata ?? [];
                $status = $metadata['status'] ?? 'pending';

                $colorMap = [
                    'approved' => '#28a745',
                    'pending' => '#ffc107',
                    'rejected' => '#dc3545',
                ];

                // Format date properly - period->date might be DateTime object or string with time
                $dateStr = Carbon::parse($period->date)->format('Y-m-d');
                $startTimeStr = Carbon::parse($period->start_time)->format('H:i:s');
                $endTimeStr = Carbon::parse($period->end_time)->format('H:i:s');

                $events[] = [
                    'id' => $appointment->id, // Use integer ID directly
                    'title' => $metadata['client_name'] ?? $appointment->name,
                    'start' => $dateStr . 'T' . $startTimeStr,
                    'end' => $dateStr . 'T' . $endTimeStr,
                    'color' => $colorMap[$status] ?? '#ffc107',
                    'backgroundColor' => $colorMap[$status] ?? '#ffc107',
                    'borderColor' => $colorMap[$status] ?? '#ffc107',
                    'textColor' => '#fff',
                    'extendedProps' => [
                        'type' => 'appointment',
                        'status' => $status,
                    ],
                ];
            }
        }

        // Müsaitlik schedule'larını getir (yeşil, arka plan)
        $availabilities = $dietitian->availabilitySchedules()
            ->whereBetween('start_date', [$fetchInfo['start'], $fetchInfo['end']])
            ->orWhere(function ($query) use ($fetchInfo) {
                $query->where('start_date', '<=', $fetchInfo['start'])
                      ->where('end_date', '>=', $fetchInfo['start']);
            })
            ->with('periods')
            ->get();

        foreach ($availabilities as $availability) {
            // Recurring schedule'lar için, görünen tarih aralığındaki günleri hesapla
            if ($availability->is_recurring && $availability->frequency === 'weekly') {
                $days = $availability->frequency_config['days'] ?? [];
                $dayMap = [
                    'monday' => 1, 'tuesday' => 2, 'wednesday' => 3,
                    'thursday' => 4, 'friday' => 5, 'saturday' => 6, 'sunday' => 0
                ];

                $start = Carbon::parse($fetchInfo['start']);
                $end = Carbon::parse($fetchInfo['end']);

                while ($start->lte($end)) {
                    $dayName = strtolower($start->format('l'));
                    if (in_array($dayName, $days)) {
                        foreach ($availability->periods as $period) {
                            $events[] = [
                                'id' => 'availability-' . $availability->id . '-' . $start->format('Y-m-d'),
                                'title' => 'Müsait',
                                'start' => $start->format('Y-m-d') . 'T' . Carbon::parse($period->start_time)->format('H:i:s'),
                                'end' => $start->format('Y-m-d') . 'T' . Carbon::parse($period->end_time)->format('H:i:s'),
                                'color' => '#28a745',
                                'backgroundColor' => 'rgba(40, 167, 69, 0.3)',
                                'borderColor' => '#28a745',
                                'display' => 'background',
                                'extendedProps' => [
                                    'type' => 'availability',
                                ],
                            ];
                        }
                    }
                    $start->addDay();
                }
            }
        }

        // Bloklanmış zamanları getir (kırmızı, arka plan)
        $blockedSchedules = $dietitian->blockedSchedules()
            ->with('periods')
            ->get();

        foreach ($blockedSchedules as $blocked) {
            if ($blocked->is_recurring && $blocked->frequency === 'weekly') {
                $days = $blocked->frequency_config['days'] ?? [];
                $start = Carbon::parse($fetchInfo['start']);
                $end = Carbon::parse($fetchInfo['end']);

                while ($start->lte($end)) {
                    $dayName = strtolower($start->format('l'));
                    if (in_array($dayName, $days)) {
                        foreach ($blocked->periods as $period) {
                            $events[] = [
                                'id' => 'blocked-' . $blocked->id . '-' . $start->format('Y-m-d'),
                                'title' => $blocked->name ?? 'Bloklanmış',
                                'start' => $start->format('Y-m-d') . 'T' . Carbon::parse($period->start_time)->format('H:i:s'),
                                'end' => $start->format('Y-m-d') . 'T' . Carbon::parse($period->end_time)->format('H:i:s'),
                                'color' => '#dc3545',
                                'backgroundColor' => 'rgba(220, 53, 69, 0.3)',
                                'borderColor' => '#dc3545',
                                'display' => 'background',
                                'extendedProps' => [
                                    'type' => 'blocked',
                                ],
                            ];
                        }
                    }
                    $start->addDay();
                }
            }
        }

        return $events;
    }

    public function eventDidMount(): string
    {
        return <<<JS
        function({ event, timeText, isStart, isEnd, isMirror, isPast, isFuture, isToday, el, view }){
            if (event.extendedProps.type !== 'availability' && event.extendedProps.type !== 'blocked') {
                el.setAttribute("x-tooltip", "tooltip");
                el.setAttribute("x-data", "{ tooltip: '"+event.title+"' }");
            }
        }
    JS;
    }

    protected function headerActions(): array
    {
        return [
            CreateAction::make()
                ->label('Randevu Oluştur')
                ->modalHeading('Randevu Oluştur'),
        ];
    }

    protected function modalActions(): array
    {
        return [
            EditAction::make()
                ->modalHeading('Randevuyu Düzenle')
                ->mountUsing(function ($form, $record, array $arguments) {
                    $scheduleId = $arguments['event']['id'] ?? $record?->id ?? null;
                    $schedule = Schedule::with('periods')->find((int) $scheduleId);

                    $formData = [];

                    if ($schedule) {
                        $period = $schedule->periods->first();
                        if ($period) {
                            $formData['appointment_date'] = Carbon::parse($schedule->start_date)->format('Y-m-d');
                            $formData['time_slot'] = Carbon::parse($period->start_time)->format('H:i') . '-' . Carbon::parse($period->end_time)->format('H:i');
                        }
                        $metadata = $schedule->metadata ?? [];
                        $formData['metadata'] = [
                            'client_name' => $metadata['client_name'] ?? '',
                            'client_email' => $metadata['client_email'] ?? '',
                            'client_phone' => $metadata['client_phone'] ?? '',
                            'note' => $metadata['note'] ?? '',
                            'status' => $metadata['status'] ?? 'pending',
                        ];
                    }

                    $form->fill($formData);
                }),
            DeleteAction::make(),
        ];
    }

    protected function viewAction(): Action
    {
        return ViewAction::make()
            ->modalHeading('Randevuyu Görüntüle')
            ->mountUsing(function ($form, $record, array $arguments) {
                $scheduleId = $arguments['event']['id'] ?? $record?->id ?? null;
                $schedule = Schedule::with('periods')->find((int) $scheduleId);

                $formData = [];

                if ($schedule) {
                    $period = $schedule->periods->first();
                    if ($period) {
                        $formData['appointment_date'] = Carbon::parse($schedule->start_date)->format('Y-m-d');
                        $formData['time_slot'] = Carbon::parse($period->start_time)->format('H:i') . '-' . Carbon::parse($period->end_time)->format('H:i');
                    }
                    $metadata = $schedule->metadata ?? [];
                    $formData['metadata'] = [
                        'client_name' => $metadata['client_name'] ?? '',
                        'client_email' => $metadata['client_email'] ?? '',
                        'client_phone' => $metadata['client_phone'] ?? '',
                        'note' => $metadata['note'] ?? '',
                        'status' => $metadata['status'] ?? 'pending',
                    ];
                }

                $form->fill($formData);
            });
    }

    public function getFormSchema(): array
    {
        return [
            Section::make('Randevu Bilgileri')
                ->schema([
                    Grid::make()->schema([
                        DatePicker::make('appointment_date')
                            ->label('Randevu Tarihi')
                            ->required()
                            ->reactive()
                            ->native(false)
                            ->minDate(now())
                            ->afterStateUpdated(fn (callable $set) => $set('time_slot', null)),

                        Select::make('time_slot')
                            ->label('Randevu Saati')
                            ->required()
                            ->searchable()
                            ->native(false)
                            ->reactive()
                            ->options(function (callable $get, $record) {
                                $date = $get('appointment_date');
                                $currentSlot = $get('time_slot');

                                if (!$date) {
                                    // Eğer sadece mevcut slot varsa onu göster
                                    if ($currentSlot) {
                                        return [$currentSlot => str_replace('-', ' - ', $currentSlot)];
                                    }
                                    return [];
                                }

                                $service = new BookAppointmentService();
                                // native(false) ile gelen date'i sadece Y-m-d formatına çevir
                                $dateFormatted = Carbon::parse($date)->format('Y-m-d');
                                $slots = $service->getAvailableSlots($dateFormatted);

                                $options = collect($slots)
                                    ->filter(fn ($slot) => $slot['is_available'] ?? false)
                                    ->mapWithKeys(fn ($slot) => [
                                        $slot['start_time'] . '-' . $slot['end_time'] =>
                                            $slot['start_time'] . ' - ' . $slot['end_time'],
                                    ])
                                    ->toArray();

                                // Mevcut slotu da ekle (düzenleme modunda silinmesin)
                                if ($currentSlot && !isset($options[$currentSlot])) {
                                    $options[$currentSlot] = str_replace('-', ' - ', $currentSlot);
                                }

                                return $options;
                            }),

                        TextInput::make('metadata.client_name')
                            ->label('Danışan Adı')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('metadata.client_email')
                            ->label('E-posta')
                            ->email()
                            ->prefix('@')
                            ->maxLength(255),

                        TextInput::make('metadata.client_phone')
                            ->label('Telefon')
                            ->prefix('+90')
                            ->mask('(999) 999 99 99')
                            ->maxLength(50),

                        Select::make('metadata.status')
                            ->label('Durum')
                            ->options(AppointmentStatusEnum::options())
                            ->default('pending'),

                        Textarea::make('metadata.note')
                            ->label('Not')
                            ->columnSpanFull()
                            ->maxLength(1000),
                    ]),
                ]),
        ];
    }

    public function resolveEventRecord(array $data): Model
    {
        $id = $data['id'] ?? null;

        if ($id) {
            return Schedule::findOrFail((int) $id);
        }

        return new Schedule();
    }
}
