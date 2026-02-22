<?php

namespace App\Filament\Resources\Blogs\Schemas;

use App\Models\Blog;
use App\Support\Localization\SupportedLocales;
use App\Support\Seo\BlogSeoAnalyzer;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ...self::contentSchema(),
                ...self::translationsSchema(),
                ...self::seoSchema(),
                ...self::socialSchema(),
                ...self::publishingSchema(),
            ]);
    }

    /**
     * @return array<Component>
     */
    public static function contentSchema(): array
    {
        return [
            Section::make('Content')
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->live(debounce: 750)
                        ->helperText(fn (Get $get): string => self::lengthHelper(
                            value: (string) $get('title'),
                            recommendedMin: 50,
                            recommendedMax: 60,
                            hardMax: 70,
                        ))
                        ->columnSpan(2),
                    TextInput::make('slug')
                        ->required()
                        ->regex('/^[a-z0-9-]+$/')
                        ->rules([
                            fn (?Blog $record) => Rule::unique('blogs', 'slug')->ignore($record),
                            fn (?Blog $record) => Rule::unique('blog_slug_redirects', 'old_slug')
                                ->where(fn (QueryBuilder $query): QueryBuilder => $record === null
                                    ? $query
                                    : $query->where('blog_id', '!=', $record->getKey())),
                        ])
                        ->maxLength(255)
                        ->live(debounce: 750)
                        ->helperText('Use lowercase letters, numbers, and hyphens. Slugs cannot reuse historical blog slugs.')
                        ->columnSpan(1),
                    RichEditor::make('content')
                        ->required()
                        ->toolbarButtons(self::writingToolbarButtons())
                        ->fileAttachmentsDisk('public')
                        ->fileAttachmentsDirectory('blogs/content')
                        ->fileAttachmentsVisibility('public')
                        ->fileAttachmentsAcceptedFileTypes(self::editorImageMimeTypes())
                        ->fileAttachmentsMaxSize(20480)
                        ->resizableImages()
                        ->live(debounce: 750)
                        ->helperText('WordPress-style writing toolbar with headings, alignment, links, tables, and image controls.')
                        ->columnSpanFull(),
                    Textarea::make('excerpt')
                        ->rows(4)
                        ->maxLength(500)
                        ->live(debounce: 750)
                        ->helperText(fn (Get $get): string => self::lengthHelper(
                            value: (string) $get('excerpt'),
                            recommendedMin: 120,
                            recommendedMax: 160,
                            hardMax: 160,
                        ))
                        ->columnSpanFull(),
                ])
                ->columns(3)
                ->columnSpanFull(),
        ];
    }

    /**
     * @return array<Component>
     */
    public static function seoSchema(): array
    {
        return [
            Section::make('SEO')
                ->schema([
                    TextInput::make('focus_keyword')
                        ->maxLength(120)
                        ->live(debounce: 750)
                        ->helperText('Primary keyword or phrase this post should rank for.')
                        ->columnSpan(1),
                    TextInput::make('canonical_url')
                        ->url()
                        ->maxLength(2048)
                        ->live(debounce: 750)
                        ->helperText('Optional canonical URL. If blank, the public blog URL is used.')
                        ->columnSpanFull(),
                    Toggle::make('robots_index')
                        ->default(true)
                        ->live(debounce: 750),
                    Toggle::make('robots_follow')
                        ->default(true)
                        ->live(debounce: 750),
                    Placeholder::make('seo_score_preview')
                        ->label('Live SEO Score')
                        ->content(fn (Get $get): string => self::scoreSummary(self::analysisResult($get)))
                        ->columnSpanFull(),
                    Placeholder::make('seo_issues_preview')
                        ->label('SEO Checklist')
                        ->content(fn (Get $get): Htmlable => new HtmlString(self::checksMarkup(self::analysisResult($get))))
                        ->columnSpanFull(),
                ])
                ->columns(3)
                ->columnSpanFull(),
        ];
    }

    /**
     * @return array<Component>
     */
    public static function translationsSchema(): array
    {
        return [
            Section::make('Translations')
                ->description('Provide translations for every supported language. English (US) is the default locale.')
                ->schema([
                    Tabs::make('Blog content translations')
                        ->tabs(self::translationTabs()),
                ])
                ->columnSpanFull(),
        ];
    }

    /**
     * @return array<Component>
     */
    public static function socialSchema(): array
    {
        return [
            Section::make('Social & Schema')
                ->schema([
                    FileUpload::make('featured_image_path')
                        ->label('Featured Image')
                        ->image()
                        ->directory('blogs/featured-images')
                        ->columnSpan(2),
                    TextInput::make('featured_image_alt')
                        ->maxLength(255)
                        ->live(debounce: 750)
                        ->columnSpan(1),
                    TextInput::make('og_title')
                        ->label('Open Graph Title')
                        ->maxLength(255)
                        ->live(debounce: 750)
                        ->helperText('Falls back to post title when empty.')
                        ->columnSpan(2),
                    Textarea::make('og_description')
                        ->label('Open Graph Description')
                        ->rows(3)
                        ->live(debounce: 750)
                        ->helperText('Falls back to excerpt when empty.')
                        ->columnSpanFull(),
                    TextInput::make('twitter_title')
                        ->maxLength(255)
                        ->live(debounce: 750)
                        ->helperText('Falls back to Open Graph title when empty.')
                        ->columnSpan(2),
                    Textarea::make('twitter_description')
                        ->rows(3)
                        ->live(debounce: 750)
                        ->helperText('Falls back to Open Graph/meta description when empty.')
                        ->columnSpanFull(),
                    Placeholder::make('og_preview')
                        ->label('Effective Open Graph Preview')
                        ->content(fn (Get $get): string => sprintf(
                            'Title: %s | Description: %s',
                            trim((string) $get('og_title')) !== '' ? (string) $get('og_title') : (string) $get('title'),
                            trim((string) $get('og_description')) !== '' ? (string) $get('og_description') : (string) $get('excerpt'),
                        ))
                        ->columnSpanFull(),
                    Placeholder::make('twitter_preview')
                        ->label('Effective Twitter Preview')
                        ->content(function (Get $get): string {
                            $title = trim((string) $get('twitter_title'));
                            $title = $title !== '' ? $title : trim((string) $get('og_title'));
                            $title = $title !== '' ? $title : (string) $get('title');

                            $description = trim((string) $get('twitter_description'));
                            $description = $description !== '' ? $description : trim((string) $get('og_description'));
                            $description = $description !== '' ? $description : (string) $get('excerpt');

                            return sprintf('Title: %s | Description: %s', $title, $description);
                        })
                        ->columnSpanFull(),
                    Select::make('schema_type')
                        ->options([
                            'BlogPosting' => 'BlogPosting',
                            'Article' => 'Article',
                            'NewsArticle' => 'NewsArticle',
                        ])
                        ->default('BlogPosting')
                        ->required()
                        ->columnSpan(1),
                    Textarea::make('schema_markup_json')
                        ->label('Custom JSON-LD')
                        ->rule('json')
                        ->rows(8)
                        ->live(debounce: 750)
                        ->helperText('Optional custom JSON-LD markup. Leave blank to auto-generate schema.')
                        ->columnSpanFull(),
                ])
                ->columns(3)
                ->columnSpanFull(),
        ];
    }

    /**
     * @return array<Component>
     */
    public static function publishingSchema(): array
    {
        return [
            Section::make('Publishing')
                ->schema([
                    Select::make('status')
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                            'scheduled' => 'Scheduled',
                            'archived' => 'Archived',
                        ])
                        ->required()
                        ->default('draft')
                        ->live(debounce: 750)
                        ->columnSpan(1),
                    DateTimePicker::make('published_at')
                        ->seconds(false)
                        ->columnSpan(1),
                    Toggle::make('is_featured')
                        ->default(false)
                        ->columnSpan(1),
                    TextInput::make('reading_time_minutes')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(600)
                        ->columnSpan(1),
                    TextInput::make('views_count')
                        ->numeric()
                        ->minValue(0)
                        ->default(0)
                        ->columnSpan(1),
                    Placeholder::make('publish_seo_advice')
                        ->label('Publish SEO Notice')
                        ->visible(fn (Get $get): bool => in_array((string) $get('status'), ['published', 'scheduled'], true))
                        ->content(fn (Get $get): Htmlable => new HtmlString(self::publishAdviceMarkup(self::analysisResult($get))))
                        ->columnSpanFull(),
                ])
                ->columns(3)
                ->columnSpanFull(),
        ];
    }

    /**
     * @param  array<string, mixed>  $analysis
     */
    protected static function scoreSummary(array $analysis): string
    {
        $grade = strtoupper((string) ($analysis['grade'] ?? 'poor'));
        $score = (int) ($analysis['score'] ?? 0);

        return "{$score}/100 ({$grade})";
    }

    /**
     * @param  array<string, mixed>  $analysis
     */
    protected static function checksMarkup(array $analysis): string
    {
        $checks = $analysis['checks'] ?? [];

        if (! is_array($checks) || $checks === []) {
            return '<div style="padding:12px 14px;border:1px solid #e4e4e7;border-radius:12px;background:#fafafa;color:#3f3f46;">No SEO checks available yet.</div>';
        }

        $items = array_map(function (mixed $check): string {
            if (! is_array($check)) {
                return '';
            }

            $status = (string) ($check['status'] ?? 'warn');
            $label = e((string) ($check['label'] ?? 'Check'));
            $message = e((string) ($check['message'] ?? ''));
            $badgeStyles = match ($status) {
                'pass' => 'background:#dcfce7;color:#166534;border:1px solid #86efac;',
                'fail' => 'background:#ffe4e6;color:#9f1239;border:1px solid #fda4af;',
                default => 'background:#fef3c7;color:#92400e;border:1px solid #fcd34d;',
            };
            $badgeLabel = strtoupper($status);

            return "<li style=\"display:flex;gap:10px;align-items:flex-start;padding:12px 14px;border:1px solid #e4e4e7;border-radius:12px;background:#ffffff;\"><span style=\"display:inline-flex;align-items:center;justify-content:center;min-width:54px;padding:4px 10px;border-radius:999px;font-size:11px;font-weight:700;line-height:1.2;{$badgeStyles}\">{$badgeLabel}</span><div style=\"display:flex;flex-direction:column;gap:6px;\"><strong style=\"font-size:14px;line-height:1.35;color:#18181b;\">{$label}</strong><span style=\"font-size:13px;line-height:1.5;color:#52525b;\">{$message}</span></div></li>";
        }, $checks);

        $summary = sprintf(
            '<div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:12px;"><span style="display:inline-flex;align-items:center;padding:5px 10px;border-radius:999px;background:#dcfce7;color:#166534;border:1px solid #86efac;font-size:12px;font-weight:700;">PASS %d</span><span style="display:inline-flex;align-items:center;padding:5px 10px;border-radius:999px;background:#fef3c7;color:#92400e;border:1px solid #fcd34d;font-size:12px;font-weight:700;">WARN %d</span><span style="display:inline-flex;align-items:center;padding:5px 10px;border-radius:999px;background:#ffe4e6;color:#9f1239;border:1px solid #fda4af;font-size:12px;font-weight:700;">FAIL %d</span></div>',
            count(array_filter($checks, fn (mixed $check): bool => is_array($check) && (($check['status'] ?? '') === 'pass'))),
            count(array_filter($checks, fn (mixed $check): bool => is_array($check) && (($check['status'] ?? '') === 'warn'))),
            count(array_filter($checks, fn (mixed $check): bool => is_array($check) && (($check['status'] ?? '') === 'fail'))),
        );

        return $summary.'<ul style="display:flex;flex-direction:column;gap:10px;margin:0;padding:0;list-style:none;">'.implode('', $items).'</ul>';
    }

    /**
     * @param  array<string, mixed>  $analysis
     */
    protected static function publishAdviceMarkup(array $analysis): string
    {
        $criticalIssues = $analysis['criticalIssues'] ?? [];

        if (! is_array($criticalIssues) || $criticalIssues === []) {
            return '<div style="padding:12px 14px;border:1px solid #86efac;border-radius:12px;background:#f0fdf4;color:#166534;font-size:13px;line-height:1.45;">No critical SEO blockers detected for publishing.</div>';
        }

        $items = array_map(
            fn (mixed $issue): string => '<li style="display:flex;gap:8px;padding:10px 12px;border-radius:10px;background:#fff7ed;border:1px solid #fdba74;"><span style="display:inline-flex;align-items:center;justify-content:center;min-width:54px;padding:4px 10px;border-radius:999px;background:#fef3c7;color:#92400e;border:1px solid #fcd34d;font-size:11px;font-weight:700;line-height:1.2;">WARN</span><span style="font-size:13px;line-height:1.5;color:#7c2d12;">'.e((string) $issue).'</span></li>',
            $criticalIssues,
        );

        return '<div style="display:flex;flex-direction:column;gap:10px;"><p style="margin:0;font-size:13px;line-height:1.5;color:#92400e;font-weight:600;">Publishing is allowed, but critical SEO issues were found:</p><ul style="display:flex;flex-direction:column;gap:8px;margin:0;padding:0;list-style:none;">'.implode('', $items).'</ul></div>';
    }

    protected static function lengthHelper(
        string $value,
        int $recommendedMin,
        int $recommendedMax,
        int $hardMax,
        ?string $fallback = null,
    ): string {
        $effective = trim($value) !== '' ? $value : (string) $fallback;
        $length = Str::length($effective);

        return "Length: {$length}. Recommended {$recommendedMin}-{$recommendedMax}, max {$hardMax}.";
    }

    /**
     * @return array{score: int, grade: string, checks: list<array{key: string, label: string, status: string, message: string, weight: int}>, criticalIssues: list<string>, metrics: array<string, mixed>}
     */
    protected static function analysisResult(Get $get): array
    {
        /** @var BlogSeoAnalyzer $analyzer */
        $analyzer = app(BlogSeoAnalyzer::class);

        return $analyzer->analyze(self::analysisPayload($get));
    }

    /**
     * @return array<string, mixed>
     */
    protected static function analysisPayload(Get $get): array
    {
        return [
            'title' => $get('title'),
            'focus_keyword' => $get('focus_keyword'),
            'slug' => $get('slug'),
            'content' => $get('content'),
            'excerpt' => $get('excerpt'),
            'featured_image_path' => $get('featured_image_path'),
            'featured_image_alt' => $get('featured_image_alt'),
            'status' => $get('status'),
            'canonical_url' => $get('canonical_url'),
            'robots_index' => $get('robots_index'),
            'robots_follow' => $get('robots_follow'),
        ];
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
     * @return array<Tab>
     */
    protected static function translationTabs(): array
    {
        $tabs = [];

        foreach (SupportedLocales::all() as $localeCode => $localeLabel) {
            $tabs[] = Tab::make($localeLabel)
                ->schema([
                    TextInput::make("title_translations.{$localeCode}")
                        ->label('Title')
                        ->nullable()
                        ->maxLength(255),
                    RichEditor::make("content_translations.{$localeCode}")
                        ->label('Content')
                        ->nullable()
                        ->toolbarButtons(self::writingToolbarButtons())
                        ->fileAttachmentsDisk('public')
                        ->fileAttachmentsDirectory('blogs/content')
                        ->fileAttachmentsVisibility('public')
                        ->fileAttachmentsAcceptedFileTypes(self::editorImageMimeTypes())
                        ->fileAttachmentsMaxSize(20480)
                        ->resizableImages()
                        ->columnSpanFull(),
                    Textarea::make("excerpt_translations.{$localeCode}")
                        ->label('Excerpt')
                        ->nullable()
                        ->rows(4)
                        ->maxLength(500)
                        ->columnSpanFull(),
                ])
                ->columns(2);
        }

        return $tabs;
    }
}
