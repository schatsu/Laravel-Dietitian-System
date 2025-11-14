<?php

namespace App\Filament\Resources;

use App\Enums\ClientActivityLevelEnum;
use App\Enums\ClientAlcoholConsumptionEnum;
use App\Enums\ClientSmokingStatusEnum;
use App\Enums\ClientStatusEnum;
use App\Enums\GenderEnum;
use App\Enums\GoalTypeEnum;
use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Danışan';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Danışan Takibi';
    protected static ?string $modelLabel = 'Danışan';
    protected static ?string $pluralModelLabel = 'Danışan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Genel Bilgiler')
                        ->schema([
                            Forms\Components\Section::make('Danışan Bilgileri')
                                ->schema([
                                    Forms\Components\Grid::make()
                                        ->schema([
                                            Forms\Components\TextInput::make('first_name')
                                                ->required()
                                                ->label('Ad')
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('last_name')
                                                ->label('Soyad')
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('email')
                                                ->label('E-posta')
                                                ->email()
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('phone')
                                                ->label('Telefon')
                                                ->mask('(999) 999 99 99')
                                                ->tel()
                                                ->maxLength(20),
                                            Forms\Components\Select::make('status')
                                                ->label('Durum')
                                                ->options(ClientStatusEnum::labels()),
                                            Forms\Components\TextInput::make('age')
                                                ->label('Yaş')
                                                ->numeric(),
                                            Forms\Components\TextInput::make('occupation')
                                                ->label('Meslek')
                                                ->maxLength(100),
                                            Forms\Components\Select::make('gender')
                                                ->label('Cinsiyet')
                                                ->options(GenderEnum::labels())
                                        ])
                                ])->collapsible()
                        ]),
                    Forms\Components\Wizard\Step::make('Fiziksel Bilgiler')
                        ->schema([
                            Forms\Components\Section::make('Fiziksel Bilgiler')
                                ->relationship('physicalProfile')
                                ->schema([
                                    Forms\Components\Grid::make()
                                        ->schema([
                                            Forms\Components\TextInput::make('initial_height')
                                                ->label('Mevcut Boy')
                                                ->prefix('cm')
                                                ->required()
                                                ->numeric(),
                                            Forms\Components\TextInput::make('initial_weight')
                                                ->label('Mevcut Kilo')
                                                ->prefix('kg')
                                                ->required()
                                                ->numeric(),
                                            Forms\Components\TextInput::make('target_weight')
                                                ->label('Hedef Kilo')
                                                ->prefix('kg')
                                                ->required()
                                                ->numeric(),
                                            Forms\Components\Select::make('goal_type')
                                                ->label('Diyet Tipi')
                                                ->required()
                                                ->options(GoalTypeEnum::options()),
                                        ]),
                                ])->collapsible(),
                        ]),
                    Forms\Components\Wizard\Step::make('Tıbbi Bilgiler')
                        ->schema([
                            Forms\Components\Section::make('Tıbbi Bilgiler')
                                ->relationship('medicalProfile')
                                ->schema([
                                    Forms\Components\Repeater::make('medical_conditions')
                                        ->label('Tıbbi Durumlar')
                                        ->schema([
                                            Forms\Components\TextInput::make('medical_conditions')
                                                ->label('Tıbbi Durumlar')
                                                ->nullable(),
                                        ])
                                        ->defaultItems(1)
                                        ->live()
                                        ->itemLabel(fn(array $state): ?string => $state['medical_conditions'] ?? null)
                                        ->addActionLabel('Tıbbi Durum Ekle')->collapsible(),
                                    Forms\Components\Repeater::make('medications')
                                        ->label('Kullandığı İlaçlar')
                                        ->schema([
                                            Forms\Components\TextInput::make('medications')
                                                ->label('Kullandığı İlaçlar')
                                                ->nullable(),
                                        ])
                                        ->defaultItems(1)
                                        ->live()
                                        ->itemLabel(fn(array $state): ?string => $state['medications'] ?? null)
                                        ->addActionLabel('İlaçla Ekle')->collapsed(),
                                    Forms\Components\Repeater::make('allergies')
                                        ->label('Alerjileri')
                                        ->schema([
                                            Forms\Components\TextInput::make('allergies')
                                                ->label('Alerjileri')
                                                ->nullable(),
                                        ])
                                        ->defaultItems(1)
                                        ->live()
                                        ->itemLabel(fn(array $state): ?string => $state['allergies'] ?? null)
                                        ->addActionLabel('Alerji Ekle')->collapsed(),
                                    Forms\Components\Repeater::make('food_allergies')
                                        ->label('Besin Alerjileri')
                                        ->schema([
                                            Forms\Components\TextInput::make('food_allergies')
                                                ->label('Besin Alerjileri')
                                                ->nullable(),
                                        ])
                                        ->defaultItems(1)
                                        ->live()
                                        ->itemLabel(fn(array $state): ?string => $state['food_allergies'] ?? null)
                                        ->addActionLabel('Alerji Ekle')->collapsed(),
                                    Forms\Components\Textarea::make('additional_medical_notes')
                                        ->label('Ek Tıbbi Notlar')
                                        ->nullable()
                                        ->rows(3)
                                        ->columnSpanFull(),
                                ])->collapsible(),
                        ]),
                    Forms\Components\Wizard\Step::make('Beslenme Alışkanlıkları')
                        ->schema([
                            Forms\Components\Section::make('Beslenme Alışkanlıkları')
                                ->relationship('nutritionProfile')
                                ->schema([
                                    Forms\Components\Repeater::make('favorite_foods')
                                        ->label('Favori Yiyecekleri')
                                        ->schema([
                                            Forms\Components\TextInput::make('favorite_foods')
                                                ->label('Favori Yiyecekleri')
                                                ->nullable(),
                                        ])
                                        ->defaultItems(1)
                                        ->addActionLabel('Favori Yiyecek Ekle')
                                        ->live()
                                        ->itemLabel(fn(array $state): ?string => $state['favorite_foods'] ?? null)
                                        ->collapsible(),
                                    Forms\Components\Repeater::make('disliked_foods')
                                        ->label('Sevmediği Yiyecekler')
                                        ->schema([
                                            Forms\Components\TextInput::make('disliked_foods')
                                                ->label('Sevmediği Yiyecekler')
                                                ->nullable(),
                                        ])
                                        ->defaultItems(1)
                                        ->live()
                                        ->itemLabel(fn(array $state): ?string => $state['disliked_foods'] ?? null)
                                        ->addActionLabel('Sevmediği Yiyecek Ekle')->collapsed(),
                                    Forms\Components\Repeater::make('dietary_restrictions')
                                        ->label('Diyet Kısıtlamaları')
                                        ->schema([
                                            Forms\Components\TextInput::make('dietary_restrictions')
                                                ->label('Diyet Kısıtlamaları')
                                                ->nullable(),
                                        ])
                                        ->defaultItems(1)
                                        ->live()
                                        ->itemLabel(fn(array $state): ?string => $state['dietary_restrictions'] ?? null)
                                        ->addActionLabel('Diyet Kısıtlamaları Ekle')->collapsed(),
                                    Forms\Components\TextInput::make('meal_frequency')
                                        ->label('Öğün Sıklığı')
                                        ->nullable()
                                        ->maxLength(50)
                                        ->placeholder('Örn: Günde 3 Kez')
                                ])->collapsible(),
                        ]),
                    Forms\Components\Wizard\Step::make('Yaşam Tarzı')
                        ->schema([
                            Forms\Components\Section::make('Yaşam Tarzı')
                                ->relationship('lifestyleProfile')
                                ->schema([
                                    Forms\Components\Grid::make()
                                        ->schema([
                                            Forms\Components\Select::make('activity_level')
                                                ->label('Aktivite Seviyesi')
                                                ->options(ClientActivityLevelEnum::options()),
                                            Forms\Components\Select::make('smoking_status')
                                                ->label('Sigara Kullanımı')
                                                ->options(ClientSmokingStatusEnum::options()),
                                            Forms\Components\Select::make('alcohol_consumption')
                                                ->label('Alkol Kullanımı')
                                                ->options(ClientAlcoholConsumptionEnum::options()),
                                            Forms\Components\TextInput::make('sleep_hours')
                                                ->label('Uyku Süresi')
                                                ->numeric()
                                                ->suffix('Saat')
                                                ->maxLength(30),
                                            Forms\Components\TextInput::make('water_intake')
                                                ->label('Günlük Su Tüketimi')
                                                ->numeric()
                                                ->suffix('lt')
                                                ->maxLength(30),
                                            Forms\Components\Textarea::make('extra_notes')
                                                ->label('Ek Notlar')
                                                ->nullable()
                                                ->maxLength(500),
                                        ])
                                ])->collapsible(),
                        ]),
                ])
                    ->columnSpanFull()->skippable(),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Ad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Soyad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->formatStateUsing(fn(ClientStatusEnum $state) => $state->label())
                    ->badge()
                    ->color(fn(ClientStatusEnum $state) => $state->color()),
                Tables\Columns\TextColumn::make('age')
                    ->label('Yaş')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('occupation')
                    ->label('Meslek')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Cinsiyet')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn(GenderEnum $state) => $state->label())
                    ->badge()
                    ->color(fn(GenderEnum $state) => $state->color()),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                ->label('Durum')
                ->options(ClientStatusEnum::options()),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('download_pdf')
                        ->label('PDF')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($record) {
                            $pdf = Pdf::loadView('client-report.follow-up-report', ['client' => $record]);
                            return response()->streamDownload(
                                fn() => print($pdf->output()),
                                "{$record->full_name}-rapor.pdf"
                            );
                        }),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
