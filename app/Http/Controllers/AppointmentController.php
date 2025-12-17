<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Jobs\SendNewAppointmentRequestToAdminJob;
use App\Services\BookAppointmentService;

class AppointmentController extends Controller
{
    public function __construct(
        protected BookAppointmentService $bookAppointmentService
    ) {}

    public function index()
    {
        return view('front.appointment.index');
    }

    public function store(StoreAppointmentRequest $request)
    {
        $data = $request->validated();

        $appointment = $this->bookAppointmentService->bookAppointment(
            date: $data['date'],
            startTime: $data['start_time'],
            endTime: $data['end_time'],
            clientData: [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'note' => $data['note'] ?? null,
                'status' => 'pending',
            ]
        );

        //SendNewAppointmentRequestToAdminJob::dispatch($appointment);

        alert(
            'Başarılı',
            "Randevu talebi başarıyla oluşturuldu.\nRandevu bilgileriniz mail olarak iletilecektir.",
            'success'
        );

        return redirect()->back();
    }

    public function getByDate()
    {
        $date = request('date');

        if (!$date) {
            return response()->json([]);
        }

        $slots = $this->bookAppointmentService->getAvailableSlots($date);


        return response()->json($slots);
    }
}
