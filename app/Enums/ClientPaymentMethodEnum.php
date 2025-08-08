<?php

namespace App\Enums;

enum ClientPaymentMethodEnum: string
{
    case CASH = 'cash';
    case CREDIT_CARD = 'credit_card';
    case BANK_TRANSFER = 'bank_transfer';
    case OTHER = 'other';

    public function label(): string
    {
        return match($this) {
            self::CASH => 'Nakit',
            self::CREDIT_CARD => 'Kredi Kartı',
            self::BANK_TRANSFER => 'Banka Transferi',
            self::OTHER => 'Diğer Yöntemler',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [
            $case->value => $case->label(),
        ])->toArray();
    }

    public function isCash(): bool
    {
        return $this === self::CASH;
    }
    public function isCreditCart(): bool
    {
        return $this === self::CREDIT_CARD;
    }
    public function isBankTransfer(): bool
    {
        return $this === self::BANK_TRANSFER;
    }
    public function isOther(): bool
    {
        return $this === self::OTHER;
    }
}
