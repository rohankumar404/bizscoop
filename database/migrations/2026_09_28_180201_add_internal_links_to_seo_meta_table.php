<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seo_meta', function (Blueprint $table) {
            if (!Schema::hasColumn('seo_meta', 'internal_links')) {
                $table->json('internal_links')->nullable()->after('focus_keyword')
                      ->comment('JSON of manual internal links: {posts: [id, ...], sections: [id, ...]}');
            }
        });
    }

    public function down(): void
    {
        Schema::table('seo_meta', function (Blueprint $table) {
            if (Schema::hasColumn('seo_meta', 'internal_links')) {
                $table->dropColumn('internal_links');
            }
        });
    }
};
