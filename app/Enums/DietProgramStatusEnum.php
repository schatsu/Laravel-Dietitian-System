<?php

namespace App\Enums;

enum DietProgramStatusEnum: string
{
    case ACTIVE = 'active';
    case PASSIVE = 'passive';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Aktif',
            self::PASSIVE => 'Pasif',
        };
    }
    public function color(): string
    {
        return match($this) {
            self::ACTIVE => 'success',
            self::PASSIVE => 'danger',
        };
    }

    public static function labels(): array
    {
        return [
            self::ACTIVE->value => self::ACTIVE->label(),
            self::PASSIVE->value => self::PASSIVE->label(),
        ];
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    public function isPassive(): bool
    {
        return $this === self::PASSIVE;
    }
}
