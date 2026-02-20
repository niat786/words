<?php

namespace App\Support\Seo;

use Illuminate\Support\Str;

class BlogSeoAnalyzer
{
    /**
     * @var array<string, array{
     *     score: int,
     *     grade: string,
     *     checks: list<array{key: string, label: string, status: string, message: string, weight: int}>,
     *     criticalIssues: list<string>,
     *     metrics: array<string, mixed>
     * }>
     */
    protected static array $cache = [];

    /**
     * @param  array<string, mixed>  $data
     * @return array{
     *     score: int,
     *     grade: string,
     *     checks: list<array{key: string, label: string, status: string, message: string, weight: int}>,
     *     criticalIssues: list<string>,
     *     metrics: array<string, mixed>
     * }
     */
    public function analyze(array $data): array
    {
        $normalizedData = $this->normalizeData($data);
        $cacheKey = md5(json_encode($normalizedData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '');

        if (array_key_exists($cacheKey, self::$cache)) {
            return self::$cache[$cacheKey];
        }

        $checks = $this->buildChecks($normalizedData);
        $metrics = $this->buildMetrics($normalizedData);

        $maxScore = array_sum(array_column($checks, 'weight'));
        $earnedScore = 0;
        $criticalIssues = [];

        foreach ($checks as $check) {
            if ($check['status'] === 'pass') {
                $earnedScore += $check['weight'];
            } elseif ($check['status'] === 'warn') {
                $earnedScore += (int) floor($check['weight'] / 2);
            }

            if (($check['critical'] ?? false) && $check['status'] === 'fail') {
                $criticalIssues[] = $check['message'];
            }
        }

        $score = $maxScore > 0
            ? max(0, min(100, (int) round(($earnedScore / $maxScore) * 100)))
            : 0;

        $grade = match (true) {
            $score >= 80 => 'good',
            $score >= 50 => 'ok',
            default => 'poor',
        };

        $result = [
            'score' => $score,
            'grade' => $grade,
            'checks' => array_map(
                fn (array $check): array => [
                    'key' => $check['key'],
                    'label' => $check['label'],
                    'status' => $check['status'],
                    'message' => $check['message'],
                    'weight' => $check['weight'],
                ],
                $checks,
            ),
            'criticalIssues' => $criticalIssues,
            'metrics' => $metrics,
        ];

        self::$cache[$cacheKey] = $result;

        return $result;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function normalizeData(array $data): array
    {
        $title = trim((string) ($data['title'] ?? ''));
        $seoTitle = trim((string) ($data['seo_title'] ?? ''));
        $focusKeyword = Str::of((string) ($data['focus_keyword'] ?? ''))->lower()->squish()->toString();
        $slug = trim((string) ($data['slug'] ?? ''));
        $contentHtml = (string) ($data['content'] ?? '');
        $contentText = $this->extractTextFromHtml($contentHtml);
        $metaDescription = trim((string) ($data['meta_description'] ?? ''));
        $excerpt = trim((string) ($data['excerpt'] ?? ''));
        $status = (string) ($data['status'] ?? 'draft');
        $canonicalUrl = trim((string) ($data['canonical_url'] ?? ''));
        $featuredImagePath = trim((string) ($data['featured_image_path'] ?? ''));
        $featuredImageAlt = trim((string) ($data['featured_image_alt'] ?? ''));

        return [
            'title' => $title,
            'seo_title' => $seoTitle,
            'effective_title' => $seoTitle !== '' ? $seoTitle : $title,
            'focus_keyword' => $focusKeyword,
            'slug' => $slug,
            'content_html' => $contentHtml,
            'content_text' => $contentText,
            'meta_description' => $metaDescription,
            'effective_meta_description' => $metaDescription !== '' ? $metaDescription : $excerpt,
            'excerpt' => $excerpt,
            'status' => $status,
            'canonical_url' => $canonicalUrl,
            'canonical_fallback_url' => $this->canonicalFallbackUrl($slug),
            'featured_image_path' => $featuredImagePath,
            'featured_image_alt' => $featuredImageAlt,
            'robots_index' => (bool) ($data['robots_index'] ?? true),
            'robots_follow' => (bool) ($data['robots_follow'] ?? true),
        ];
    }

    /**
     * @param  array<string, mixed>  $normalizedData
     * @return list<array{key: string, label: string, status: string, message: string, weight: int, critical: bool}>
     */
    protected function buildChecks(array $normalizedData): array
    {
        $checks = [];

        $effectiveTitle = (string) $normalizedData['effective_title'];
        $focusKeyword = (string) $normalizedData['focus_keyword'];
        $effectiveMetaDescription = (string) $normalizedData['effective_meta_description'];
        $slug = Str::lower((string) $normalizedData['slug']);
        $contentText = (string) $normalizedData['content_text'];
        $contentHtml = (string) $normalizedData['content_html'];
        $status = (string) $normalizedData['status'];
        $canonicalUrl = (string) $normalizedData['canonical_url'];
        $featuredImagePath = (string) $normalizedData['featured_image_path'];
        $featuredImageAlt = (string) $normalizedData['featured_image_alt'];

        $wordCount = $this->countWords($contentText);
        $headingCount = $this->countHeadings($contentHtml);
        $linkStats = $this->linkStats($contentHtml);
        $imageStats = $this->imageStats($contentHtml);
        $keywordInTitle = $focusKeyword !== '' && str_contains(Str::lower($effectiveTitle), $focusKeyword);
        $keywordInSlug = $focusKeyword !== '' && str_contains($slug, Str::slug($focusKeyword));
        $keywordInIntro = $focusKeyword !== '' && str_contains(Str::lower(Str::substr($contentText, 0, 220)), $focusKeyword);
        $keywordOccurrences = $focusKeyword !== '' ? $this->countKeywordOccurrences($contentText, $focusKeyword) : 0;
        $keywordDensity = $wordCount > 0 ? ($keywordOccurrences / $wordCount) * 100 : 0.0;

        $checks[] = $this->check(
            key: 'title_length',
            label: 'SEO title length',
            status: match (true) {
                $effectiveTitle === '' => 'fail',
                Str::length($effectiveTitle) >= 50 && Str::length($effectiveTitle) <= 60 => 'pass',
                Str::length($effectiveTitle) >= 40 && Str::length($effectiveTitle) <= 70 => 'warn',
                default => 'fail',
            },
            message: match (true) {
                $effectiveTitle === '' => 'SEO title is missing.',
                Str::length($effectiveTitle) >= 50 && Str::length($effectiveTitle) <= 60 => 'SEO title length is optimal.',
                default => 'SEO title should ideally be 50-60 characters.',
            },
            weight: 12,
            critical: true,
        );

        $checks[] = $this->check(
            key: 'keyword_in_title',
            label: 'Focus keyword in title',
            status: match (true) {
                $focusKeyword === '' => 'fail',
                $keywordInTitle => 'pass',
                default => 'fail',
            },
            message: match (true) {
                $focusKeyword === '' => 'Focus keyword is required for SEO analysis.',
                $keywordInTitle => 'Focus keyword is present in the title.',
                default => 'Focus keyword is missing in the title.',
            },
            weight: 10,
            critical: true,
        );

        $checks[] = $this->check(
            key: 'meta_description',
            label: 'Meta description quality (from excerpt)',
            status: match (true) {
                $effectiveMetaDescription === '' => 'fail',
                Str::length($effectiveMetaDescription) >= 120 && Str::length($effectiveMetaDescription) <= 160 => 'pass',
                Str::length($effectiveMetaDescription) >= 90 && Str::length($effectiveMetaDescription) <= 160 => 'warn',
                default => 'fail',
            },
            message: match (true) {
                $effectiveMetaDescription === '' => 'Excerpt is missing; this is used as the meta description.',
                Str::length($effectiveMetaDescription) >= 120 && Str::length($effectiveMetaDescription) <= 160 => 'Description length is optimal.',
                default => 'Excerpt should ideally be 120-160 characters.',
            },
            weight: 12,
            critical: true,
        );

        $checks[] = $this->check(
            key: 'keyword_in_meta',
            label: 'Focus keyword in meta description',
            status: match (true) {
                $focusKeyword === '' => 'fail',
                $effectiveMetaDescription !== '' && str_contains(Str::lower($effectiveMetaDescription), $focusKeyword) => 'pass',
                default => 'warn',
            },
            message: match (true) {
                $focusKeyword === '' => 'Focus keyword is required for metadata checks.',
                $effectiveMetaDescription !== '' && str_contains(Str::lower($effectiveMetaDescription), $focusKeyword) => 'Focus keyword is present in meta description.',
                default => 'Add focus keyword to meta description for stronger relevance.',
            },
            weight: 8,
            critical: false,
        );

        $checks[] = $this->check(
            key: 'keyword_in_slug',
            label: 'Focus keyword in slug',
            status: match (true) {
                $focusKeyword === '' => 'fail',
                $keywordInSlug => 'pass',
                default => 'warn',
            },
            message: match (true) {
                $focusKeyword === '' => 'Focus keyword is required to evaluate slug.',
                $keywordInSlug => 'Slug includes the focus keyword.',
                default => 'Slug should include the focus keyword.',
            },
            weight: 7,
            critical: false,
        );

        $checks[] = $this->check(
            key: 'content_length',
            label: 'Content length',
            status: match (true) {
                $wordCount >= 600 => 'pass',
                $wordCount >= 300 => 'warn',
                default => 'fail',
            },
            message: match (true) {
                $wordCount >= 600 => 'Content length is strong for SEO.',
                $wordCount >= 300 => 'Content can be expanded for better SEO depth.',
                default => 'Content is too short. Aim for at least 300 words.',
            },
            weight: 10,
            critical: true,
        );

        $checks[] = $this->check(
            key: 'keyword_distribution',
            label: 'Keyword distribution',
            status: match (true) {
                $focusKeyword === '' => 'fail',
                $keywordInIntro && $keywordDensity >= 0.3 && $keywordDensity <= 2.5 => 'pass',
                $keywordOccurrences > 0 => 'warn',
                default => 'fail',
            },
            message: match (true) {
                $focusKeyword === '' => 'Focus keyword is required for distribution checks.',
                $keywordInIntro && $keywordDensity >= 0.3 && $keywordDensity <= 2.5 => 'Keyword distribution is balanced.',
                $keywordOccurrences > 0 => 'Adjust keyword usage and include it early in content.',
                default => 'Focus keyword is not used in content.',
            },
            weight: 10,
            critical: false,
        );

        $checks[] = $this->check(
            key: 'headings',
            label: 'Heading structure',
            status: match (true) {
                $headingCount >= 2 => 'pass',
                $headingCount === 1 => 'warn',
                default => 'fail',
            },
            message: match (true) {
                $headingCount >= 2 => 'Content includes a healthy heading structure.',
                $headingCount === 1 => 'Add more headings to improve scanability.',
                default => 'No headings found. Add headings for structure.',
            },
            weight: 6,
            critical: false,
        );

        $checks[] = $this->check(
            key: 'links',
            label: 'Internal and external links',
            status: match (true) {
                $linkStats['internal'] >= 1 && $linkStats['external'] >= 1 => 'pass',
                $linkStats['internal'] >= 1 || $linkStats['external'] >= 1 => 'warn',
                default => 'fail',
            },
            message: match (true) {
                $linkStats['internal'] >= 1 && $linkStats['external'] >= 1 => 'Internal and external linking looks good.',
                $linkStats['internal'] >= 1 || $linkStats['external'] >= 1 => 'Add both internal and external links for stronger SEO context.',
                default => 'Add at least one internal and one external link.',
            },
            weight: 5,
            critical: false,
        );

        $checks[] = $this->check(
            key: 'image_alt',
            label: 'Image alt text',
            status: match (true) {
                $featuredImagePath === '' && $imageStats['total'] === 0 => 'warn',
                $featuredImagePath !== '' && $featuredImageAlt === '' => 'fail',
                $imageStats['total'] > 0 && $imageStats['with_alt'] < $imageStats['total'] => 'warn',
                default => 'pass',
            },
            message: match (true) {
                $featuredImagePath === '' && $imageStats['total'] === 0 => 'Add a featured image with alt text for richer SERP previews.',
                $featuredImagePath !== '' && $featuredImageAlt === '' => 'Featured image alt text is missing.',
                $imageStats['total'] > 0 && $imageStats['with_alt'] < $imageStats['total'] => 'Some inline images are missing alt text.',
                default => 'Image alt text is properly configured.',
            },
            weight: 8,
            critical: false,
        );

        $checks[] = $this->check(
            key: 'robots',
            label: 'Robots directives',
            status: match (true) {
                in_array($status, ['published', 'scheduled'], true)
                    && (! $normalizedData['robots_index'] || ! $normalizedData['robots_follow']) => 'fail',
                default => 'pass',
            },
            message: match (true) {
                in_array($status, ['published', 'scheduled'], true)
                    && (! $normalizedData['robots_index'] || ! $normalizedData['robots_follow']) => 'Published/scheduled posts should generally be index,follow.',
                default => 'Robots directives are valid.',
            },
            weight: 7,
            critical: true,
        );

        $checks[] = $this->check(
            key: 'canonical',
            label: 'Canonical URL',
            status: match (true) {
                $canonicalUrl === '' => 'warn',
                filter_var($canonicalUrl, FILTER_VALIDATE_URL) !== false => 'pass',
                default => 'fail',
            },
            message: match (true) {
                $canonicalUrl === '' => 'Canonical URL will fallback to the public blog URL.',
                filter_var($canonicalUrl, FILTER_VALIDATE_URL) !== false => 'Canonical URL is valid.',
                default => 'Canonical URL is invalid.',
            },
            weight: 5,
            critical: false,
        );

        return $checks;
    }

    /**
     * @param  array<string, mixed>  $normalizedData
     * @return array<string, mixed>
     */
    protected function buildMetrics(array $normalizedData): array
    {
        $effectiveTitle = (string) $normalizedData['effective_title'];
        $focusKeyword = (string) $normalizedData['focus_keyword'];
        $contentHtml = (string) $normalizedData['content_html'];
        $contentText = (string) $normalizedData['content_text'];
        $wordCount = $this->countWords($contentText);
        $keywordOccurrences = $focusKeyword !== '' ? $this->countKeywordOccurrences($contentText, $focusKeyword) : 0;
        $keywordDensity = $wordCount > 0 ? round(($keywordOccurrences / $wordCount) * 100, 2) : 0.0;
        $linkStats = $this->linkStats($contentHtml);
        $imageStats = $this->imageStats($contentHtml);

        return [
            'title_length' => Str::length($effectiveTitle),
            'seo_title_length' => Str::length((string) $normalizedData['seo_title']),
            'meta_description_length' => Str::length((string) $normalizedData['meta_description']),
            'word_count' => $wordCount,
            'heading_count' => $this->countHeadings($contentHtml),
            'keyword_occurrences' => $keywordOccurrences,
            'keyword_density' => $keywordDensity,
            'internal_links_count' => $linkStats['internal'],
            'external_links_count' => $linkStats['external'],
            'images_count' => $imageStats['total'],
            'images_with_alt_count' => $imageStats['with_alt'],
            'canonical_url_provided' => (string) $normalizedData['canonical_url'] !== '',
            'canonical_fallback_url' => (string) $normalizedData['canonical_fallback_url'],
        ];
    }

    /**
     * @return array{key: string, label: string, status: string, message: string, weight: int, critical: bool}
     */
    protected function check(
        string $key,
        string $label,
        string $status,
        string $message,
        int $weight,
        bool $critical,
    ): array {
        return [
            'key' => $key,
            'label' => $label,
            'status' => $status,
            'message' => $message,
            'weight' => $weight,
            'critical' => $critical,
        ];
    }

    protected function canonicalFallbackUrl(string $slug): string
    {
        if ($slug === '') {
            return rtrim(config('app.url'), '/').'/blog';
        }

        return rtrim(config('app.url'), '/').'/blog/'.$slug;
    }

    protected function extractTextFromHtml(string $html): string
    {
        return Str::of(html_entity_decode(strip_tags($html)))
            ->squish()
            ->toString();
    }

    protected function countWords(string $text): int
    {
        preg_match_all('/\p{L}[\p{L}\p{N}\-\']*/u', $text, $matches);

        return count($matches[0]);
    }

    protected function countHeadings(string $html): int
    {
        return (int) preg_match_all('/<h[1-6][^>]*>/i', $html);
    }

    protected function countKeywordOccurrences(string $contentText, string $focusKeyword): int
    {
        if ($focusKeyword === '') {
            return 0;
        }

        $escapedKeyword = preg_quote($focusKeyword, '/');
        $pattern = '/(?<!\p{L})'.$escapedKeyword.'(?!\p{L})/iu';

        preg_match_all($pattern, Str::lower($contentText), $matches);

        return count($matches[0]);
    }

    /**
     * @return array{internal: int, external: int}
     */
    protected function linkStats(string $html): array
    {
        preg_match_all('/<a\b[^>]*\bhref\s*=\s*([\'"])(.*?)\1/iu', $html, $matches);
        $hrefs = $matches[2] ?? [];
        $internal = 0;
        $external = 0;
        $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);

        foreach ($hrefs as $href) {
            $trimmedHref = trim((string) $href);

            if ($trimmedHref === '' || str_starts_with($trimmedHref, '#')) {
                continue;
            }

            if (str_starts_with($trimmedHref, '/')) {
                $internal++;

                continue;
            }

            $linkHost = parse_url($trimmedHref, PHP_URL_HOST);

            if (! is_string($linkHost) || $linkHost === '') {
                continue;
            }

            if ($appHost !== null && strcasecmp($linkHost, $appHost) === 0) {
                $internal++;
            } else {
                $external++;
            }
        }

        return [
            'internal' => $internal,
            'external' => $external,
        ];
    }

    /**
     * @return array{total: int, with_alt: int}
     */
    protected function imageStats(string $html): array
    {
        $total = (int) preg_match_all('/<img\b[^>]*>/iu', $html);
        $withAlt = (int) preg_match_all('/<img\b[^>]*\balt\s*=\s*([\'"]).*?\1[^>]*>/iu', $html);

        return [
            'total' => $total,
            'with_alt' => $withAlt,
        ];
    }
}
