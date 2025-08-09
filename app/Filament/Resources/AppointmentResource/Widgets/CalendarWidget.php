<?php

namespace App\Filament\Resources\AppointmentResource\Widgets;

use App\Models\Appointment;
use App\Models\AppointmentSlot;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DateTimePicker;
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

class CalendarWidget extends FullCalendarWidget
{
    public string|null|Model $model = Appointment::class;
    public function fetchEvents(array $fetchInfo): array
    {
        return Appointment::with('slot')
            ->whereHas('slot', function ($query) use ($fetchInfo) {
                $query->whereBetween('date', [$fetchInfo['start'], $fetchInfo['end']]);
            })
            ->get()
            ->map(function (Appointment $appointment) {
                $color = $appointment->slot->is_active
                    ? '#3490dc'
                    : '#95a5a6';

                return [
                    'id'    => $appointment->id,
                    'title' => $appointment->name,
                    'start' => $appointment->slot->date . 'T' . $appointment->slot->start_time,
                    'end'   => $appointment->slot->date . 'T' . $appointment->slot->end_time,
                    'color' => $color,
                ];
            })
            ->toArray();
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
                ->mutateRecordDataUsing(function (array $data) {
                    if (isset($data['appointment_slot_id'])) {
                        $slot = AppointmentSlot::query()->find($data['appointment_slot_id']);
                        if ($slot) {

                            $data['slot_date'] = $slot->date;

                            $data['appointment_slot_id'] = $slot->id;
                        }
                    }

                    return $data;
                })
            ,
            DeleteAction::make(),
        ];
    }
    protected function viewAction(): Action
    {
        return ViewAction::make()
            ->modalHeading('Randevuyu Görüntüle')
            ->mutateRecordDataUsing(function (array $data) {
                if (isset($data['appointment_slot_id'])) {
                    $slot = AppointmentSlot::query()->find($data['appointment_slot_id']);
                    if ($slot) {

                        $data['slot_date'] = $slot->date;

                        $data['appointment_slot_id'] = $slot->id;
                    }
                }

                return $data;
            });
    }
    public function getFormSchema(): array
    {
        return [
            Section::make('Randevu Bilgileri')
                ->schema([
                    Grid::make()->schema([
                        Select::make('slot_date')
                            ->label('Randevu Tarihi')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->reactive()
                            ->options(function () {
                                return AppointmentSlot::query()
                                    ->where('is_active', true)
                                    ->where('is_booked', false)
                                    ->orderBy('date')
                                    ->pluck('date', 'date')
                                    ->unique();
                            }),

                        Select::make('appointment_slot_id')
                            ->label('Randevu Saati')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->disabled(fn (callable $get) => !$get('slot_date'))
                            ->options(function (callable $get) {
                                $date = $get('slot_date');
                                if (!$date) {
                                    return [];
                                }

                                return AppointmentSlot::query()
                                    ->where('date', $date)
                                    ->where('is_active', true)
                                    ->where('is_booked', false)
                                    ->orderBy('start_time')
                                    ->get()
                                    ->mapWithKeys(fn ($slot) => [
                                        $slot->id => $slot->start_time . ' - ' . $slot->end_time,
                                    ]);
                            }),

                        TextInput::make('name')->label('Danışan Adı')->required()->maxLength(255),
                        TextInput::make('email')->label('E-posta')->email()->prefix('@')->maxLength(255),
                        TextInput::make('phone')->label('Telefon')->prefix('+90')->mask('(999) 999 99 99')->maxLength(50),
                        Textarea::make('note')->label('Not')->maxLength(1000),
                    ]),
                ]),
        ];
    }
}
