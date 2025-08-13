<?php

namespace App\Enums;

enum ProgramDayEnum: string
{
    case MONDAY = 'monday';
    case TUESDAY = 'tuesday';
    case WEDNESDAY = 'wednesday';
    case THURSDAY = 'thursday';
    case FRIDAY = 'friday';
    case SATURDAY = 'saturday';
    case SUNDAY = 'sunday';

    public function label(): string
    {
        return match($this) {
            self::MONDAY => 'Pzt',
            self::TUESDAY => 'Sal',
            self::WEDNESDAY => 'Çar',
            self::THURSDAY => 'Per',
            self::FRIDAY => 'Cum',
            self::SATURDAY => 'Cmt',
            self::SUNDAY => 'Paz',
            default => 'Bilinmiyor',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [
            $case->value => $case->label(),
        ])->toArray();
    }
}
