<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seo_meta', function (Blueprint $table) {
            if (!Schema::hasColumn('seo_meta', 'robots')) {
                $table->string('robots')->nullable()->after('twitter_card')
                      ->comment('e.g. index,follow | noindex,follow | noindex,nofollow');
            }
            if (!Schema::hasColumn('seo_meta', 'focus_keyword')) {
                $table->string('focus_keyword')->nullable()->after('robots');
            }
        });
    }

    public function down(): void
    {
        Schema::table('seo_meta', function (Blueprint $table) {
            $table->dropColumn(['robots', 'focus_keyword']);
        });
    }
};
