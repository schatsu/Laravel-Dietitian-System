<?php

namespace App\Filament\Pages;

use App\Settings\SocialSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageSocialSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-device-phone-mobile';
    protected static ?string $navigationGroup = 'Site Ayarları';
    protected static ?int $navigationSort = 2;
    protected static ?string $title = 'Sosyal Medya';
    protected static ?string $navigationLabel = 'Sosyal Medya';

    protected static string $settings = SocialSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Sosyal Medya Ayarları')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('instagram')
                                    ->label('Instagram URL')
                                    ->url()
                                    ->nullable(),
                                Forms\Components\TextInput::make('facebook')
                                    ->label('Facebook URL')
                                    ->url()
                                    ->nullable(),
                                Forms\Components\TextInput::make('x')
                                    ->label('X URL')
                                    ->url()
                                    ->nullable(),
                                Forms\Components\TextInput::make('linkedin')
                                    ->label('LinkedIn URL')
                                    ->url()
                                    ->nullable(),
                                Forms\Components\TextInput::make('g_plus')
                                    ->label('Google+ URL')
                                    ->url()
                                    ->nullable(),
                                Forms\Components\TextInput::make('youtube')
                                    ->label('Youtube URL')
                                    ->url()
                                    ->nullable(),
                                Forms\Components\TextInput::make('vimeo')
                                    ->label('Vimeo URL')
                                    ->url()
                                    ->nullable(),
                                Forms\Components\TextInput::make('email')
                                    ->label('E-Posta Adresi')
                                    ->nullable(),
                            ])
                    ])->collapsible()
            ]);
    }
}
