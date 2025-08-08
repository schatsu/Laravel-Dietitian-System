<?php

namespace App\Enums;

enum ClientActivityLevelEnum: string
{
    case SEDENTARY = 'sedentary'; // Hareketsiz (Masabaşı işi)
    case LIGHT = 'light'; // Az Aktif (Haftada 1-3 gün egzersiz)
    case MODERATE = 'moderate'; // Orta Aktif (Haftada 3-5 gün egzersiz)
    case VERY_ACTIVE = 'very_active'; // Çok Aktif (Haftada 6-7 gün egzersiz)
    case EXTREMELY_ACTIVE = 'extremely_active'; // Son derece Aktif (Günde 2 kez egzersiz)

    public function label(): string
    {
        return match ($this) {
            self::SEDENTARY => 'Hareketsiz (Masabaşı işi)',
            self::LIGHT => 'Az Aktif (Haftada 1-3 gün egzersiz)',
            self::MODERATE => 'Orta Aktif (Haftada 3-5 gün egzersiz)',
            self::VERY_ACTIVE => 'Çok Aktif (Haftada 6-7 gün egzersiz)',
            self::EXTREMELY_ACTIVE => 'Son derece Aktif (Günde 2 kez egzersiz)',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [
            $case->value => $case->label()
        ])->toArray();
    }
}
