<?php

namespace App\Filament\Resources\Blogs\Pages;

use App\Filament\Resources\Blogs\BlogResource;
use App\Filament\Resources\Blogs\Schemas\BlogForm;
use App\Support\Seo\BlogSeoAnalyzer;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Support\Facades\Auth;

class CreateBlog extends CreateRecord
{
    use HasWizard;

    protected static string $resource = BlogResource::class;

    /**
     * @var list<string>
     */
    protected array $criticalSeoIssues = [];

    /**
     * @return array<Step>
     */
    protected function getSteps(): array
    {
        return [
            Step::make('Content')
                ->schema(BlogForm::contentSchema()),
            Step::make('Translations')
                ->schema(BlogForm::translationsSchema()),
            Step::make('SEO')
                ->schema(BlogForm::seoSchema()),
            Step::make('Social & Schema')
                ->schema(BlogForm::socialSchema()),
            Step::make('Publishing')
                ->schema(BlogForm::publishingSchema()),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        $analysis = app(BlogSeoAnalyzer::class)->analyze($data);

        $data['seo_score'] = $analysis['score'];
        $data['seo_grade'] = $analysis['grade'];
        $this->criticalSeoIssues = $analysis['criticalIssues'];

        return $data;
    }

    protected function afterCreate(): void
    {
        if (! in_array((string) $this->record->status, ['published', 'scheduled'], true)) {
            return;
        }

        if ($this->criticalSeoIssues === []) {
            return;
        }

        Notification::make()
            ->warning()
            ->title('Published with SEO warnings')
            ->body(implode(PHP_EOL, $this->criticalSeoIssues))
            ->send();
    }
}
