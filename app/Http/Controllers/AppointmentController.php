<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Jobs\SendNewAppointmentRequestToAdminJob;
use App\Services\BookAppointmentService;
use App\Traits\Responder;
use Illuminate\Support\Carbon;

class AppointmentController extends Controller
{
    use Responder;
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

        SendNewAppointmentRequestToAdminJob::dispatch($appointment);

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
            return $this->validationError('Tarih alanı zorunludur.');
        }

        try {
            $slots = $this->bookAppointmentService->getAvailableSlots($date);

            return $this->success(
                $slots,
                Carbon::parse($date)->translatedFormat('d F Y') . ' tarihi için müsait saatler.'
            );
        } catch (\Exception $e) {
            return $this->serverError('Saatler yüklenirken bir hata oluştu.');
        }
    }
}
