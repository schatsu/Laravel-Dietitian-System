<?php

namespace App\Filament\Pages;

use App\Settings\SiteSettings;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageSiteSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Site Ayarları';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Genel';
    protected static ?string $navigationLabel = 'Genel';
    protected static string $view = 'filament.pages.manage-general-setting';


    protected static string $settings = SiteSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Genel Ayarlar')
                    ->schema([
                        Forms\Components\Tabs::make('Genel Ayarlar')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('Site Bilgileri')
                                    ->schema([
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('site_name')
                                                    ->label('Site Adı')
                                                    ->nullable()
                                                    ->maxLength(100),
                                                Forms\Components\TextInput::make('site_description')
                                                    ->label('Site Açıklaması')
                                                    ->nullable()
                                                    ->maxLength(255),
                                                Forms\Components\FileUpload::make('site_logo_light')
                                                    ->label('Site Logo (Açık Mod)')
                                                    ->image()
                                                    ->directory('logo')
                                                    ->maxSize(2048)
                                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                                                    ->nullable(),
                                                Forms\Components\FileUpload::make('site_logo_dark')
                                                    ->label('Site Logo (Koyu Tema)')
                                                    ->image()
                                                    ->directory('logo')
                                                    ->maxSize(2048)
                                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                                                    ->required(),
                                                Forms\Components\FileUpload::make('site_favicon')
                                                    ->label('Site Favicon')
                                                    ->image()
                                                    ->directory('logo')
                                                    ->maxSize(2048)
                                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                                                    ->nullable(),
                                            ])
                                    ]),
                                Forms\Components\Tabs\Tab::make('İletişim Bilgileri')
                                    ->schema([
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('phone')
                                                    ->label('Telefon')
                                                    ->tel()
                                                    ->mask('(999) 999-9999'),
                                                Forms\Components\TextInput::make('site_email')
                                                    ->label('E-posta')
                                                    ->email(),
                                                Forms\Components\TextInput::make('whatsapp')
                                                    ->label('Whatsapp Numarası')
                                                    ->tel()
                                                    ->mask('(999) 999-9999'),
                                                Forms\Components\TextInput::make('address')
                                                    ->label('Fiziksel Adres')
                                                    ->helperText('Adres yazmaya başlayın, Google önerileri çıkacak.')
                                                    ->extraAttributes([
                                                        'id' => 'address-autocomplete',
                                                        'autocomplete' => 'off'
                                                    ]),
                                                Forms\Components\TextInput::make('latitude')
                                                    ->label('Enlem (Lat)')
                                                    ->readOnly(),
                                                Forms\Components\TextInput::make('longitude')
                                                    ->label('Boylam (Lng)')
                                                    ->readOnly(),

                                            ])
                                    ]),
                                Forms\Components\Tabs\Tab::make('GA4 (Google Analytics)')
                                    ->schema([
                                        Forms\Components\Textarea::make('google_analytics')
                                            ->label('Google Analytics Kodu')
                                            ->placeholder('GA4 Kodu')
                                            ->rows(10)
                                            ->columnSpanFull()
                                    ]),
                                Forms\Components\Tabs\Tab::make('Google Tag Manager')
                                    ->schema([
                                        Forms\Components\Textarea::make('google_tag_manager')
                                            ->label('Google Tag Manager')
                                            ->placeholder('Google Tag Manager Kodu')
                                            ->rows(10)
                                            ->columnSpanFull()
                                    ]),
                                Forms\Components\Tabs\Tab::make('Meta Pixels')
                                    ->schema([
                                        Forms\Components\Textarea::make('meta_pixels')
                                            ->label('Meta Pixels Kodu')
                                            ->placeholder('Meta Pixels Kodu')
                                            ->rows(10)
                                            ->columnSpanFull()
                                    ])
                            ])
                    ]),
            ]);
    }
}
