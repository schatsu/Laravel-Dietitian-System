<?php

namespace App\Exports;

use App\Models\ClientPayment;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientPaymentsExport implements FromCollection, WithHeadings
{
    public function collection(): Collection|\Illuminate\Support\Collection
    {
        return ClientPayment::with('client')->get()->map(function ($payment) {
            return [
                'Danışan' => $payment->client->full_name,
                'Seans Tarihi' => $payment->session_date,
                'Miktar' => $payment->amount. ' '. '₺',
                'Ödeme Türü' => $payment->payment_method->label(),
                'Ödeme Durumu' => $payment->payment_status->label(),
                'Ödeme Tarihi' => $payment->payment_date,
                'Notlar' => $payment->notes,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Danışan',
            'Seans Tarihi',
            'Miktar',
            'Ödeme Türü',
            'Ödeme Durumu',
            'Ödeme Tarihi',
            'Notlar',
        ];
    }
}
