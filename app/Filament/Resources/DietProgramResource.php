<?php

namespace App\Filament\Resources;

use App\Enums\DietProgramStatusEnum;
use App\Filament\Resources\DietProgramResource\Pages;
use App\Filament\Resources\DietProgramResource\RelationManagers;
use App\Models\DietProgram;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DietProgramResource extends Resource
{
    protected static ?string $model = DietProgram::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Diyet Programları';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Diyet Programları';
    protected static ?string $modelLabel = 'Diyet Programı';
    protected static ?string $pluralModelLabel = 'Diyet Programı';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.full_name')
                    ->label('Danışan Adı')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                ->label('Program Adı')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                ->sortable()
                ->label('Başlangıç Tarihi')
                ->date('Y-m-d'),
                Tables\Columns\TextColumn::make('target_date')
                    ->sortable()
                    ->label('Hedef Tarihi')
                    ->date('Y-m-d'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->formatStateUsing(fn(DietProgramStatusEnum $state) => $state->label())
                    ->badge()
                    ->color(fn(DietProgramStatusEnum $state) => $state->color()),
            ])
            ->filters([
                //
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
            'index' => Pages\ListDietPrograms::route('/'),
            'create' => Pages\CreateDietProgram::route('/create'),
            'edit' => Pages\EditDietProgram::route('/{record}/edit'),
            'program' => Pages\DietProgramEditor::route('/{record}/program'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
