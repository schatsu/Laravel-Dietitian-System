<?php

namespace App\Enums;

enum ClientAlcoholConsumptionEnum: string
{
    case NONE = 'none';
    case OCCASIONAL = 'occasional';
    case REGULAR = 'regular';

    public function label(): string
    {
        return match ($this) {
            self::NONE => 'Alkol kullanmıyor',
            self::OCCASIONAL => 'Ara sıra',
            self::REGULAR => 'Düzenli',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [
            $case->value => $case->label(),
        ])->toArray();
    }
}
