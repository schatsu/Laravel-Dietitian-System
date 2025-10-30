<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        if (Schema::hasTable('settings')) {
            $this->migrator->add('general.site_name' ,'Ayşe Yılmaz');
            $this->migrator->add('general.site_description', 'Dyt.Ayşe Yılmaz');
            $this->migrator->add('general.phone', '533 333 33 33');
            $this->migrator->add('general.site_email', 'ayseyilmaz@diyetisyen.com');
            $this->migrator->add('general.whatsapp', '533 333 33 33');
            $this->migrator->add('general.address', 'Tüpraş Stadyumu, Vişnezade, Dolmabahçe Caddesi, Beşiktaş/İstanbul, Türkiye');
            $this->migrator->add('general.latitude', 41.0390029);
            $this->migrator->add('general.longitude', 28.9945064);
            $this->migrator->add('general.site_logo_light');
            $this->migrator->add('general.site_logo_dark');
            $this->migrator->add('general.site_favicon');
            $this->migrator->add('general.google_analytics');
            $this->migrator->add('general.google_tag_manager');
            $this->migrator->add('general.meta_pixel');
        }
    }
};
