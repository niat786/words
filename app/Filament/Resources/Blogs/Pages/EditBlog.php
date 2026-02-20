<?php

namespace App\Filament\Resources\Blogs\Pages;

use App\Filament\Resources\Blogs\BlogResource;
use App\Filament\Resources\Blogs\Schemas\BlogForm;
use App\Support\Seo\BlogSeoAnalyzer;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;

class EditBlog extends EditRecord
{
    protected static string $resource = BlogResource::class;

    /**
     * @var list<string>
     */
    protected array $criticalSeoIssues = [];

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            ...BlogForm::contentSchema(),
            ...BlogForm::seoSchema(),
            ...BlogForm::socialSchema(),
            ...BlogForm::publishingSchema(),
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $analysis = app(BlogSeoAnalyzer::class)->analyze($data);

        $data['seo_score'] = $analysis['score'];
        $data['seo_grade'] = $analysis['grade'];
        $this->criticalSeoIssues = $analysis['criticalIssues'];

        return $data;
    }

    protected function afterSave(): void
    {
        if (! in_array((string) $this->record->status, ['published', 'scheduled'], true)) {
            return;
        }

        if ($this->criticalSeoIssues === []) {
            return;
        }

        Notification::make()
            ->warning()
            ->title('Saved with SEO warnings')
            ->body(implode(PHP_EOL, $this->criticalSeoIssues))
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
