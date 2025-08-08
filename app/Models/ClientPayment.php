<?php

namespace App\Models;

use App\Enums\ClientPaymentMethodEnum;
use App\Enums\ClientPaymentStatusEnum;
use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class ClientPayment extends Model
{
    protected $fillable = [
        'client_id','amount', 'payment_method','session_date',
        'payment_status', 'payment_date', 'notes'
    ];

    protected function casts(): array
    {
        return [
            'payment_method' => ClientPaymentMethodEnum::class,
            'payment_status' => ClientPaymentStatusEnum::class,
            'session_date' => 'date',
            'date' => 'date',
            'amount' => 'decimal:2'
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function getWhichPaymentMethodAttribute(): string
    {
        return match ($this->payment_method)
        {
            ClientPaymentMethodEnum::CASH => 'Nakit',
            ClientPaymentMethodEnum::CREDIT_CARD => 'Kredi Kartı',
            ClientPaymentMethodEnum::BANK_TRANSFER => 'Banka Transferi',
            ClientPaymentMethodEnum::OTHER => 'Diğer',
            default => 'Bilinmiyor'
        };
    }
    public function getPaymentMethodColorAttribute(): string
    {
        return match ($this->payment_method)
        {
            ClientPaymentMethodEnum::CASH => 'badge-success',
            ClientPaymentMethodEnum::CREDIT_CARD => 'badge-primary',
            ClientPaymentMethodEnum::BANK_TRANSFER => 'badge-warning',
            ClientPaymentMethodEnum::OTHER => 'badge-secondary',
            default => 'badge-danger'
        };
    }
    public function getPaymentStatusFormattedAttribute(): string
    {
        return match ($this->payment_status)
        {
            ClientPaymentStatusEnum::COMPLETED => 'Tamamlandı',
            ClientPaymentStatusEnum::PENDING => 'Ödeme Bekliyor',
            ClientPaymentStatusEnum::CANCELLED => 'Ödeme İptal Edildi',
            ClientPaymentStatusEnum::REFUNDED => 'İade Edildi',
            default => 'Bilinmiyor'
        };
    }
    public function getPaymentStatusColorAttribute(): string
    {
        return match ($this->payment_status)
        {
            ClientPaymentStatusEnum::COMPLETED => 'badge-success',
            ClientPaymentStatusEnum::CANCELLED => 'badge-primary',
            ClientPaymentStatusEnum::PENDING => 'badge-warning',
            ClientPaymentStatusEnum::REFUNDED => 'badge-secondary',
            default => 'badge-danger'
        };
    }
    public function getPaymentDateFormattedAttribute(): string
    {
        $date = $this->payment_date ?? '';

        return Carbon::parse($date)->format('d/m/Y');
    }
    public function getSessionDateFormattedAttribute(): string
    {
        $date = $this->session_date ?? '';

        return Carbon::parse($date)->format('d/m/Y');
    }
}
