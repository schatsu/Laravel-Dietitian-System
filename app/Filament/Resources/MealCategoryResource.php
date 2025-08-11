<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MealCategoryResource\Pages;
use App\Models\MealCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MealCategoryResource extends Resource
{
    protected static ?string $model = MealCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3-center-left';
    protected static ?string $navigationGroup = 'Diyet Programları';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Besin Kategorileri';
    protected static ?string $modelLabel = 'Besin Kategorisi';
    protected static ?string $pluralModelLabel = 'Besin Kategorileri';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Besin Kategorisi Bilgileri')
                ->schema([
                    Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Kategori Adı')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('order')
                            ->label('Sıra')
                            ->numeric()
                            ->required(),

                        Forms\Components\Toggle::make('is_popular')
                            ->label('Popüler mi?')
                            ->default(false),

                        Forms\Components\Toggle::make('status')
                            ->label('Aktif mi?')
                            ->default(true),
                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('Sıra')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Kategori Adı')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_popular')
                    ->label('Popüler')
                    ->boolean(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Durum')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma Tarihi')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Güncellenme Tarihi')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order')
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMealCategories::route('/'),
            'create' => Pages\CreateMealCategory::route('/create'),
            'edit' => Pages\EditMealCategory::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
