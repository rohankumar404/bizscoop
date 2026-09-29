<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Seed the SEO settings group into the settings table.
     * Uses insertOrIgnore so existing rows are never overwritten.
     */
    public function up(): void
    {
        $defaults = [
            // ── General SEO ──────────────────────────────────────
            ['key' => 'seo_canonical_base',          'value' => 'https://bizscoopmena.com', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'seo_force_https',             'value' => '1',                        'group' => 'seo', 'type' => 'boolean'],
            ['key' => 'seo_force_non_www',           'value' => '1',                        'group' => 'seo', 'type' => 'boolean'],
            ['key' => 'seo_remove_trailing_slash',   'value' => '1',                        'group' => 'seo', 'type' => 'boolean'],
            ['key' => 'seo_canonical_tags_enabled',  'value' => '1',                        'group' => 'seo', 'type' => 'boolean'],
            ['key' => 'seo_sitemap_enabled',         'value' => '1',                        'group' => 'seo', 'type' => 'boolean'],

            // ── Robots Control ───────────────────────────────────
            ['key' => 'seo_robots_login',            'value' => 'noindex,follow',           'group' => 'seo', 'type' => 'text'],
            ['key' => 'seo_robots_register',         'value' => 'noindex,follow',           'group' => 'seo', 'type' => 'text'],
            ['key' => 'seo_robots_dashboard',        'value' => 'noindex,follow',           'group' => 'seo', 'type' => 'text'],
            ['key' => 'seo_robots_search',           'value' => 'noindex,follow',           'group' => 'seo', 'type' => 'text'],
            ['key' => 'seo_robots_default',          'value' => 'index,follow',             'group' => 'seo', 'type' => 'text'],

            // ── Sitemap Control ──────────────────────────────────
            ['key' => 'seo_sitemap_articles',        'value' => '1',                        'group' => 'seo', 'type' => 'boolean'],
            ['key' => 'seo_sitemap_sections',        'value' => '1',                        'group' => 'seo', 'type' => 'boolean'],
            ['key' => 'seo_sitemap_static_pages',    'value' => '1',                        'group' => 'seo', 'type' => 'boolean'],

            // ── Internal Linking ─────────────────────────────────
            ['key' => 'seo_related_articles_enabled','value' => '1',                        'group' => 'seo', 'type' => 'boolean'],
            ['key' => 'seo_related_articles_count',  'value' => '3',                        'group' => 'seo', 'type' => 'text'],
            ['key' => 'seo_min_articles_for_section','value' => '6',                        'group' => 'seo', 'type' => 'text'],
        ];

        $now = now();
        foreach ($defaults as $row) {
            DB::table('settings')->insertOrIgnore(array_merge($row, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }

    public function down(): void
    {
        $keys = [
            'seo_canonical_base','seo_force_https','seo_force_non_www','seo_remove_trailing_slash',
            'seo_canonical_tags_enabled','seo_sitemap_enabled',
            'seo_robots_login','seo_robots_register','seo_robots_dashboard','seo_robots_search','seo_robots_default',
            'seo_sitemap_articles','seo_sitemap_sections','seo_sitemap_static_pages',
            'seo_related_articles_enabled','seo_related_articles_count','seo_min_articles_for_section',
        ];
        DB::table('settings')->whereIn('key', $keys)->delete();
    }
};
