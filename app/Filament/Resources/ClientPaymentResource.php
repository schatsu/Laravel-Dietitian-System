<?php

namespace App\Filament\Resources;

use App\Enums\ClientPaymentMethodEnum;
use App\Enums\ClientPaymentStatusEnum;
use App\Exports\ClientPaymentsExport;
use App\Filament\Resources\ClientPaymentResource\Pages;
use App\Filament\Resources\ClientPaymentResource\RelationManagers;
use App\Models\ClientPayment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ClientPaymentResource extends Resource
{
    protected static ?string $model = ClientPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Danışan';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Danışan Ödemeleri';
    protected static ?string $modelLabel = 'Ödeme';
    protected static ?string $pluralModelLabel = 'Ödeme';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Danışan Ödeme Bilgileri')
                ->schema([
                    Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Select::make('client_id')
                            ->label('Danışan Adı')
                            ->relationship('client', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\DatePicker::make('session_date')
                            ->label('Seans Tarihi')
                            ->placeholder(Carbon::today()->format('Y-m-d'))
                            ->locale('tr')
                            ->timezone('Europe/Istanbul')
                            ->defaultFocusedDate(now())
                            ->format('Y-m-d')
                            ->native(false),
                        Forms\Components\TextInput::make('amount')
                            ->label('Miktar')
                            ->prefix('₺')
                            ->required()
                            ->numeric(),
                        Forms\Components\Select::make('payment_method')
                        ->label('Ödeme Türü')
                        ->options(ClientPaymentMethodEnum::options())
                        ->required(),
                        Forms\Components\Select::make('payment_status')
                            ->label('Ödeme Türü')
                            ->options(ClientPaymentStatusEnum::options())
                            ->required(),
                        Forms\Components\DatePicker::make('payment_date')
                            ->label('Ödeme Tarihi')
                            ->placeholder(Carbon::today()->format('Y-m-d'))
                            ->locale('tr')
                            ->timezone('Europe/Istanbul')
                            ->defaultFocusedDate(now())
                            ->format('Y-m-d')
                            ->native(false),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notlar')
                            ->columnSpanFull(),
                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Action::make('export_excel')
                    ->label('Excel İndir')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn () => Excel::download(new ClientPaymentsExport, 'odeme-kaydi.xlsx')),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('client.full_name')
                    ->label('Danışan Adı')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('session_date')
                    ->label('Seans Tarihi')
                    ->date('Y-m-d')
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Miktar')
                    ->money('TRY', locale: 'tr_TR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Ödeme Türü')
                    ->badge()
                    ->color(fn ($state) => match ($state?->value) {
                        ClientPaymentMethodEnum::CASH->value => 'success',
                        ClientPaymentMethodEnum::CREDIT_CARD->value => 'warning',
                        ClientPaymentMethodEnum::BANK_TRANSFER->value => 'info',
                        ClientPaymentMethodEnum::OTHER->value => 'gray',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn ($state) => $state?->label() ?? '-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Ödeme Durumu')
                    ->badge()
                    ->color(fn ($state) => match ($state?->value) {
                        ClientPaymentStatusEnum::PENDING->value => 'warning',
                        ClientPaymentStatusEnum::COMPLETED->value => 'success',
                        ClientPaymentStatusEnum::CANCELLED->value => 'danger',
                        ClientPaymentStatusEnum::REFUNDED->value => 'gray',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn ($state) => $state?->label() ?? '-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Ödeme Tarihi')
                    ->date('Y-m-d')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Ödeme Türü')
                    ->options(\App\Enums\ClientPaymentMethodEnum::options()),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Ödeme Durumu')
                    ->options(\App\Enums\ClientPaymentStatusEnum::options()),

                Tables\Filters\SelectFilter::make('client_id')
                    ->label('Danışan')
                    ->relationship('client', 'first_name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('session_date')
                    ->label('Seans Tarihi')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Başlangıç Tarihi')->native(false),
                        Forms\Components\DatePicker::make('until')->label('Bitiş Tarihi')->native(false),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($query, $date) => $query->whereDate('session_date', '>=', $date))
                            ->when($data['until'], fn ($query, $date) => $query->whereDate('session_date', '<=', $date));
                    }),
            ])

            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListClientPayments::route('/'),
            'create' => Pages\CreateClientPayment::route('/create'),
            'edit' => Pages\EditClientPayment::route('/{record}/edit'),
        ];
    }
}
