<?php

namespace App\Enums;

enum AppointmentStatusEnum: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::APPROVED => 'Onaylandı',
            self::PENDING => 'Beklemede',
            self::REJECTED => 'Reddedildi',
            default => 'Bilinmiyor',
        };
    }

    public static function labels(): array
    {
        return [
            self::APPROVED->value => self::APPROVED->label(),
            self::PENDING->value => self::PENDING->label(),
            self::REJECTED->value => self::REJECTED->label(),
        ];
    }

    public function color(): string
    {
        return match($this) {
            self::APPROVED => 'success',
            self::PENDING => 'warning',
            self::REJECTED => 'danger',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [
            $case->value => $case->label(),
        ])->toArray();
    }
}
