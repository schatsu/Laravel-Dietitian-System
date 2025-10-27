<?php

namespace App\Filament\Pages;

use App\Models\About;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Str;

class AboutSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.pages.about-settings';
    protected static ?string $navigationGroup = 'Site';
    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Hakkımda';
    protected static ?string $navigationLabel = 'Hakkımda';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';


    public ?array $data = [];

    public function mount(): void
    {
        $about = About::query()->firstOrCreate([], [
            'title' => '',
            'slug' => '',
            'content' => '',
        ]);

        $this->form->fill($about->toArray());
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Hakkımda')
            ->schema([
                Grid::make()
                ->schema([
                    TextInput::make('title')
                        ->label('Başlık')
                        ->live(onBlur: true)
                        ->required()
                        ->afterStateUpdated(function (string $operation, $state, callable $set) {
                            $set('slug', Str::slug($state));
                        })
                        ->maxLength(255),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(255),

                    RichEditor::make('content')
                        ->label('İçerik')
                        ->required()
                        ->toolbarButtons([
                            'attachFiles',
                            'blockquote',
                            'bold',
                            'bulletList',
                            'codeBlock',
                            'h1',
                            'h2',
                            'h3',
                            'italic',
                            'link',
                            'orderedList',
                            'redo',
                            'strike',
                            'underline',
                            'undo',
                        ])
                        ->columnSpanFull(),
                ])
            ])
            ->collapsible()
        ])->statePath('data');
    }

    public function save(): void
    {
        $about = About::query()->firstOrCreate([], [
            'title' => '',
            'slug' => '',
            'content' => '',
        ]);

        $about->update($this->form->getState());

        Notification::make()
            ->title('Hakkımda Güncellendi!')
            ->success()
            ->send();
    }
}
