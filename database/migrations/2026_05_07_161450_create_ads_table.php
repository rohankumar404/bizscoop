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
        Schema::create('ads', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('title');
            $blueprint->string('position'); // e.g. 'sidebar', 'header', 'inline'
            $blueprint->string('type')->default('image'); // 'image', 'code'
            $blueprint->text('content')->nullable(); // For code/HTML ads
            $blueprint->string('image')->nullable();
            $blueprint->string('link')->nullable();
            $blueprint->boolean('is_active')->default(true);
            $blueprint->timestamp('expires_at')->nullable();
            $blueprint->integer('views')->default(0);
            $blueprint->integer('clicks')->default(0);
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
