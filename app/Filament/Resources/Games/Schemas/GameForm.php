<?php

namespace App\Filament\Resources\Games\Schemas;

use App\Models\Game;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class GameForm
{
    /**
     * @var array<string, string>
     */
    protected static array $gameOptions = [
        'wordle' => 'Wordle',
        'spellbee' => 'SpellBee: Spelling Bee Game',
        'quiz' => 'Quiz',
        'tiles' => 'Tiles',
        'memory' => 'Memory',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Game Selection')
                    ->schema([
                        Placeholder::make('game_name')
                            ->label('Game Name')
                            ->content(fn (Get $get): string => self::gameLabel((string) $get('game_key')))
                            ->columnSpanFull()
                            ->visible(fn (string $operation): bool => $operation !== 'create'),
                        Select::make('game_key')
                            ->label('Game')
                            ->options(fn (): array => self::availableGameOptions())
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Already created games are not available here. Use Edit to update existing games.')
                            ->columnSpanFull()
                            ->visible(fn (string $operation): bool => $operation === 'create'),
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true),
                                Toggle::make('is_default')
                                    ->label('Default Game')
                                    ->helperText('Only one game can be default.')
                                    ->default(false),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),

                Section::make('SEO & Metadata')
                    ->visible(fn (Get $get, string $operation): bool => $operation !== 'create' || filled($get('game_key')))
                    ->description('Configure SEO fields for the selected game page.')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        RichEditor::make('content')
                            ->label('Content')
                            ->toolbarButtons(self::writingToolbarButtons())
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('games/content')
                            ->fileAttachmentsVisibility('public')
                            ->fileAttachmentsAcceptedFileTypes(self::editorImageMimeTypes())
                            ->fileAttachmentsMaxSize(20480)
                            ->resizableImages()
                            ->extraInputAttributes(['class' => 'min-h-40'])
                            ->helperText('WordPress-style writing toolbar with image upload, table tools, headings, and alignment controls.')
                            ->columnSpanFull(),
                        TextInput::make('focus_keyword')
                            ->maxLength(120)
                            ->columnSpan(1),
                        Textarea::make('meta_description')
                            ->required()
                            ->rows(3)
                            ->maxLength(160)
                            ->columnSpanFull(),
                        TextInput::make('canonical_url')
                            ->url()
                            ->maxLength(2048)
                            ->columnSpanFull(),
                        Toggle::make('robots_index')
                            ->default(true),
                        Toggle::make('robots_follow')
                            ->default(true),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Section::make('Schema & Social')
                    ->visible(fn (Get $get, string $operation): bool => $operation !== 'create' || filled($get('game_key')))
                    ->description('Add ad/schema markup and social metadata.')
                    ->schema([
                        Textarea::make('ads_schema_markup')
                            ->label('Ads / Schema Markup (JSON-LD, scripts)')
                            ->rows(8)
                            ->columnSpanFull(),
                        TextInput::make('og_title')
                            ->label('Open Graph Title')
                            ->maxLength(255)
                            ->columnSpan(2),
                        Textarea::make('og_description')
                            ->label('Open Graph Description')
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('twitter_title')
                            ->maxLength(255)
                            ->columnSpan(2),
                        Textarea::make('twitter_description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }

    protected static function gameLabel(string $gameKey): string
    {
        return self::$gameOptions[$gameKey] ?? ucfirst($gameKey);
    }

    /**
     * @return array<string | array<string>>
     */
    protected static function writingToolbarButtons(): array
    {
        return [
            ['undo', 'redo'],
            ['bold', 'italic', 'underline', 'strike', 'link', 'textColor', 'highlight'],
            ['h1', 'h2', 'h3', 'small', 'lead'],
            ['alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
            ['bulletList', 'orderedList', 'blockquote', 'horizontalRule', 'codeBlock'],
            ['table', 'attachFiles'],
        ];
    }

    /**
     * @return array<string>
     */
    protected static function editorImageMimeTypes(): array
    {
        return [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/avif',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected static function availableGameOptions(): array
    {
        $existingGameKeys = Game::query()->pluck('game_key')->all();

        return array_filter(
            self::$gameOptions,
            fn (string $label, string $gameKey): bool => ! in_array($gameKey, $existingGameKeys, true),
            ARRAY_FILTER_USE_BOTH,
        );
    }
}
