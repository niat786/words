<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use UnitEnum;

class SiteSettings extends Page
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string | UnitEnum | null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Site Settings';

    protected string $view = 'filament.pages.site-settings';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getSettings()->attributesToArray());
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            $this->getFormContentComponent(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General')
                    ->description('Update site-wide brand identity.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('site_name')
                            ->maxLength(255)
                            ->placeholder('Wordly')
                            ->helperText('Used as the global site name fallback when a page title is missing.')
                            ->columnSpan(1),
                        TextInput::make('site_tagline')
                            ->maxLength(255)
                            ->placeholder('Unlimited word games')
                            ->columnSpan(1),
                        Textarea::make('site_description')
                            ->rows(4)
                            ->maxLength(500)
                            ->placeholder('Add a short description for footer/about sections.')
                            ->columnSpanFull(),
                    ]),
                Section::make('Branding')
                    ->description('Upload images used in navigation, browser tabs, and mobile home screens.')
                    ->columns(3)
                    ->schema([
                        FileUpload::make('logo_path')
                            ->label('Logo')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('site/branding')
                            ->imageEditor()
                            ->columnSpan(1),
                        FileUpload::make('favicon_path')
                            ->label('Favicon')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('site/branding')
                            ->acceptedFileTypes([
                                'image/png',
                                'image/x-icon',
                                'image/svg+xml',
                            ])
                            ->columnSpan(1),
                        FileUpload::make('apple_touch_icon_path')
                            ->label('Apple Touch Icon')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('site/branding')
                            ->columnSpan(1),
                    ]),
                Section::make('Head Scripts')
                    ->description('Manage site-wide scripts and verification markup injected into the <head> tag.')
                    ->columns(2)
                    ->schema([
                        Textarea::make('global_header_code')
                            ->label('Global Header Code')
                            ->rows(8)
                            ->helperText('Add optional scripts or verification meta tags.')
                            ->columnSpanFull(),
                    ]),
                Section::make('Social Media Links')
                    ->description('Add public profile URLs for your social channels.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('facebook_url')
                            ->label('Facebook URL')
                            ->url()
                            ->maxLength(2048)
                            ->columnSpan(1),
                        TextInput::make('instagram_url')
                            ->label('Instagram URL')
                            ->url()
                            ->maxLength(2048)
                            ->columnSpan(1),
                        TextInput::make('youtube_url')
                            ->label('YouTube URL')
                            ->url()
                            ->maxLength(2048)
                            ->columnSpan(1),
                        TextInput::make('x_url')
                            ->label('X URL')
                            ->url()
                            ->maxLength(2048)
                            ->columnSpan(1),
                        TextInput::make('pinterest_url')
                            ->label('Pinterest URL')
                            ->url()
                            ->maxLength(2048)
                            ->columnSpan(1),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $settings = $this->getSettings();
        $oldBrandingPaths = $this->extractBrandingPaths($settings);

        $settings->fill($this->form->getState());
        $settings->save();
        $this->deleteUnusedBrandingPaths($oldBrandingPaths, $settings);

        Notification::make()
            ->success()
            ->title('Site settings saved')
            ->send();
    }

    protected function getFormContentComponent(): Component
    {
        return Form::make([
            EmbeddedSchema::make('form'),
        ])
            ->id('form')
            ->livewireSubmitHandler('save')
            ->footer([
                Actions::make([
                    Action::make('save')
                        ->label('Save Settings')
                        ->icon(Heroicon::OutlinedCheckCircle)
                        ->submit('save')
                        ->keyBindings(['mod+s']),
                ]),
            ]);
    }

    protected function getSettings(): Setting
    {
        return Setting::query()->firstOrCreate(
            [],
            [
                'default_game' => 'wordle',
                'enabled_games' => null,
            ],
        );
    }

    /**
     * @return array<string, string|null>
     */
    protected function extractBrandingPaths(Setting $settings): array
    {
        return [
            'logo_path' => $settings->logo_path,
            'favicon_path' => $settings->favicon_path,
            'apple_touch_icon_path' => $settings->apple_touch_icon_path,
        ];
    }

    /**
     * @param  array<string, string|null>  $oldBrandingPaths
     */
    protected function deleteUnusedBrandingPaths(array $oldBrandingPaths, Setting $settings): void
    {
        $currentPaths = array_values(array_filter([
            $settings->logo_path,
            $settings->favicon_path,
            $settings->apple_touch_icon_path,
        ]));

        foreach ($oldBrandingPaths as $oldPath) {
            if (! is_string($oldPath) || trim($oldPath) === '') {
                continue;
            }

            if (Str::startsWith($oldPath, ['http://', 'https://', '/'])) {
                continue;
            }

            if (in_array($oldPath, $currentPaths, true)) {
                continue;
            }

            Storage::disk('public')->delete($oldPath);
        }
    }
}
