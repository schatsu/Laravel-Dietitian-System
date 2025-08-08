<?php

namespace App\Enums;

enum ClientPaymentStatusEnum: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Beklemede',
            self::COMPLETED => 'Ödeme Alındı',
            self::CANCELLED => 'İptal Edildi',
            self::REFUNDED => 'İade Edildi',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [
            $case->value => $case->label(),
        ])->toArray();
    }
}
