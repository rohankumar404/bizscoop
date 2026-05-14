<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->timestamp('starts_at')->nullable()->after('is_active');
            // Change position to text/json for multiple selections
            // If doctrine/dbal isn't installed, this might fail, so we can drop and recreate it or just add a new column if necessary.
            // Let's try standard change:
            $table->json('position')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn('starts_at');
            $table->string('position')->change();
        });
    }
};
