<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Storage;

class Schema extends Component
{
    public $type;
    public $data;

    public function __construct($type = 'Organization', $data = [])
    {
        $this->type = $type;
        $this->data = $data;
    }

    public function render(): View|Closure|string
    {
        $schema = match ($this->type) {
            'NewsArticle' => $this->newsArticleSchema(),
            'BreadcrumbList' => $this->breadcrumbSchema(),
            default => $this->organizationSchema(),
        };

        return view('components.schema', ['json' => json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)]);
    }

    protected function organizationSchema()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => setting('site_name', 'BizScoop'),
            'url' => route('frontend.home'),
            'logo' => setting('site_logo') ? Storage::url(setting('site_logo')) : '',
            'sameAs' => [
                setting('social_twitter'),
                setting('social_linkedin'),
                setting('social_instagram'),
            ]
        ];
    }

    protected function newsArticleSchema()
    {
        $post = $this->data['post'];
        $translation = $post->translate();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $translation->title,
            'image' => [
                $post->getFirstMediaUrl('featured_image')
            ],
            'datePublished' => $post->published_at->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $post->author->name,
                'url' => '#' 
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => setting('site_name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => setting('site_logo') ? Storage::url(setting('site_logo')) : ''
                ]
            ],
            'description' => $translation->excerpt
        ];
    }

    protected function breadcrumbSchema()
    {
        $items = $this->data['items'] ?? [];
        $listItems = [];

        foreach ($items as $index => $item) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url']
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems
        ];
    }
}
