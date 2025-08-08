<?php

namespace App\Enums;

enum ClientSmokingStatusEnum: string
{
    case NON_SMOKER = 'non_smoker';
    case OCCASIONAL = 'occasional';
    case REGULAR = 'regular';

    public function label(): string
    {
        return match ($this) {
            self::NON_SMOKER => 'Sigara kullanmıyor',
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
