<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // ── Display toggles ──────────────────────────
            $table->boolean('show_in_hero')->default(false)->after('show_in_homepage');
            $table->boolean('show_in_footer')->default(true)->after('show_in_hero');
            $table->boolean('show_in_mobile_menu')->default(true)->after('show_in_footer');

            // ── Content toggles ──────────────────────────
            $table->boolean('allow_sponsored_posts')->default(false)->after('show_in_mobile_menu');
            $table->boolean('enable_trending_section')->default(true)->after('allow_sponsored_posts');
            $table->boolean('enable_category_slider')->default(false)->after('enable_trending_section');
            $table->boolean('hide_from_search')->default(false)->after('enable_category_slider');
            $table->boolean('premium_badge')->default(false)->after('hide_from_search');
            $table->boolean('mega_menu')->default(false)->after('premium_badge');

            // ── Layout controls ──────────────────────────
            $table->string('layout_type')->default('grid')->after('mega_menu');
            // grid | slider | featured | mixed
            $table->unsignedTinyInteger('posts_per_section')->default(6)->after('layout_type');
            $table->unsignedTinyInteger('hero_priority')->default(0)->after('posts_per_section');
            $table->unsignedSmallInteger('desktop_menu_order')->default(0)->after('hero_priority');
            $table->unsignedSmallInteger('mobile_menu_order')->default(0)->after('desktop_menu_order');

            // ── Style ────────────────────────────────────
            $table->string('color', 20)->nullable()->after('mobile_menu_order');
            $table->string('icon', 100)->nullable()->after('color');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'show_in_hero', 'show_in_footer', 'show_in_mobile_menu',
                'allow_sponsored_posts', 'enable_trending_section',
                'enable_category_slider', 'hide_from_search', 'premium_badge',
                'mega_menu', 'layout_type', 'posts_per_section',
                'hero_priority', 'desktop_menu_order', 'mobile_menu_order',
                'color', 'icon',
            ]);
        });
    }
};
