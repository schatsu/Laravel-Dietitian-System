<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetAppointmentSlotsRequest;
use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\AppointmentSlot;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('front.appointment.index');
    }

    public function store(StoreAppointmentRequest $request)
    {
        $attributes = collect($request->validated());

        $slot = AppointmentSlot::query()->findOrFail($attributes->get('appointment_slot_id'));

        Appointment::query()->create($attributes->toArray());

        $slot->update(['is_booked' => true]);

        toast('Randevu başarıyla oluşturuldu.', 'success');

        return redirect()->back();
    }

    public function getByDate(GetAppointmentSlotsRequest $request)
    {
        $attributes = collect($request->validated());

        $slots = AppointmentSlot::query()
            ->select(['id', 'start_time', 'end_time'])
            ->where('is_active', true)
            ->where('is_booked', false)
            ->whereDate('date', $attributes->get('date'))
            ->orderBy('start_time')
            ->get();

        return response()->json($slots);
    }
}
