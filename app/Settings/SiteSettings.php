<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SiteSettings extends Settings
{
    public ?string $site_name = null;
    public ?string $site_description = null;

    public ?string $phone = null;
    public ?string $site_email = null;
    public ?string $whatsapp = null;


    public ?string $address = null;
    public ?float $latitude = null;
    public ?float $longitude = null;

    public ?string $site_logo_light = null;
    public ?string $site_logo_dark = null;
    public ?string $site_favicon = null;

    public ?string $google_analytics = null;
    public ?string $google_tag_manager = null;
    public ?string $meta_pixel = null;


    public static function group(): string
    {
        return 'general';
    }

    public function formatPhone(?string $number): ?string
    {
        if (! $number) {
            return null;
        }

        $phone = preg_replace('/\D+/', '', $number);
        $phone = ltrim($phone, '0');
        return '90' . $phone;
    }

    public function whatsappPhoneFormatted(): ?string
    {
        return $this->formatPhone($this->whatsapp);
    }

    public function phoneFormatted(): ?string
    {
        return $this->formatPhone($this->phone);
    }
}
