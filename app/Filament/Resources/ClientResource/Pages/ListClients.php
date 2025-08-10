<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Enums\ClientStatusEnum;
use App\Enums\GenderEnum;
use App\Filament\Resources\ClientResource;
use App\Models\Client;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;

class ListClients extends ListRecords
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createClient')
                ->label('Danışan Oluştur')
                ->modalHeading('Yeni Danışan Oluştur')
                ->modalSubmitActionLabel('Oluştur')
                ->form([
                    Section::make('Danışan Bilgileri')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    TextInput::make('first_name')
                                        ->required()
                                        ->label('Ad')
                                        ->maxLength(255),
                                    TextInput::make('last_name')
                                        ->label('Soyad')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('email')
                                        ->label('E-posta')
                                        ->email()
                                        ->maxLength(255),
                                    TextInput::make('phone')
                                        ->label('Telefon')
                                        ->mask('(999) 999 99 99')
                                        ->tel()
                                        ->maxLength(20),
                                    Select::make('status')
                                        ->label('Durum')
                                        ->options(ClientStatusEnum::labels()),
                                    TextInput::make('age')
                                        ->label('Yaş')
                                        ->numeric(),
                                    TextInput::make('occupation')
                                        ->label('Meslek')
                                        ->maxLength(100),
                                    Select::make('gender')
                                        ->label('Cinsiyet')
                                        ->options(GenderEnum::labels())
                                ])
                        ])
                ])
                ->action(function (array $data, Action $action) {
                    $client = Client::query()->create($data);
                    $action->redirect(ClientResource::getUrl('edit', ['record' => $client]));
                })
                ->icon('heroicon-m-user-plus')
                ->button(),
        ];
    }
}
