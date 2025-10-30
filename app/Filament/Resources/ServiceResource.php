<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static ?string $navigationGroup = 'Site';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationLabel = 'Hizmetler';
    protected static ?string $pluralLabel = 'Hizmetler';
    protected static ?string $modelLabel = 'Hizmet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make()
                    ->schema([
                        Forms\Components\Wizard\Step::make('Genel Bilgiler')
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Hizmet Adı')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                                $set('slug', Str::slug($state));
                                            }),

                                        Forms\Components\TextInput::make('slug')
                                            ->label('Slug')
                                            ->unique(ignoreRecord: true)
                                            ->required()
                                            ->maxLength(255),

                                        Forms\Components\Toggle::make('status')
                                            ->label('Yayında mı?')
                                            ->default(false),

                                        Forms\Components\TextInput::make('order')
                                            ->label('Sıra')
                                            ->numeric()
                                            ->minValue(1),
                                    ]),
                            ]),

                        Forms\Components\Wizard\Step::make('İçerik')
                            ->schema([
                                Forms\Components\Textarea::make('description')
                                    ->label('Kısa Açıklama')
                                    ->columnSpanFull(),

                                Forms\Components\RichEditor::make('content')
                                    ->label('Detaylı İçerik')
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'underline',
                                        'strike',
                                        'link',
                                        'orderedList',
                                        'bulletList',
                                        'blockquote',
                                        'codeBlock',
                                        'h2',
                                        'h3',
                                        'alignLeft',
                                        'alignCenter',
                                        'alignRight',
                                        'undo',
                                        'redo',
                                    ])
                                    ->columnSpanFull(),

                                SpatieMediaLibraryFileUpload::make('images')
                                    ->imageEditor()
                                    ->collection('images')
                                    ->directory('services')
                                    ->label('Görsel')
                                    ->image(),
                            ]),

                        Forms\Components\Wizard\Step::make('SEO Ayarları')
                            ->schema([
                                Forms\Components\TextInput::make('seo_title')
                                    ->label('SEO Başlık')
                                    ->maxLength(255),

                                Forms\Components\Textarea::make('seo_description')
                                    ->label('SEO Açıklaması')
                                    ->columnSpanFull(),

                                Forms\Components\SpatieTagsInput::make('seo_keywords')
                                    ->type('service_seo_keywords')
                                    ->label('SEO Anahtar Kelimeleri'),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->skippable(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Hizmet Adı')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Yayında mı?')
                    ->boolean(),
                Tables\Columns\TextColumn::make('order')
                    ->label('Sıra')
                    ->numeric()
                    ->sortable(),
                SpatieMediaLibraryImageColumn::make('images')
                    ->label('Görsel')
                    ->circular(),
            ])->defaultSort('order', 'asc')
            ->reorderable('order')
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Düzenle'),
                Tables\Actions\DeleteAction::make()->label('Sil'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Seçilenleri Sil'),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
