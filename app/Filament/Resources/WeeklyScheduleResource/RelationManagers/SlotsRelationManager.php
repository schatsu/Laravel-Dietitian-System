<?php

namespace App\Filament\Resources\WeeklyScheduleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Attributes\On;

class SlotsRelationManager extends RelationManager
{
    protected static string $relationship = 'slots';
    protected static ?string $title = 'Slotlar';
    protected static ?string $label = 'Slot';

    protected $listeners = [
        'updateSlots' => '$refresh',
    ];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TimePicker::make('start_time')
                    ->label('Başlangıç Saati')
                    ->seconds(false)
                    ->native(false)
                    ->required(),

                Forms\Components\TimePicker::make('end_time')
                    ->seconds(false)
                    ->native(false)
                    ->label('Bitiş Saati')
                    ->required(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif mi?')
                    ->default(true),
            ]);
    }


    public function table(Table $table): Table
    {
        return $table
            ->description('📅 Not: Randevular 30 günlük periyot için listelenmektedir.')
            ->recordTitleAttribute('slot')
            ->columns([
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Başlangıç')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('H:i')),

                Tables\Columns\TextColumn::make('end_time')
                    ->label('Bitiş')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('H:i')),

                Tables\Columns\TextColumn::make('date')
                    ->label('Tarih')
                    ->date('d.m.Y'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Aktif mi?'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

}
