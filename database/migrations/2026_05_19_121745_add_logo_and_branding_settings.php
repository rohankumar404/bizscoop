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
        // Update site_logo to group 'branding' if it exists, or create it
        $logoExists = DB::table('settings')->where('key', 'site_logo')->exists();
        if ($logoExists) {
            DB::table('settings')->where('key', 'site_logo')->update(['group' => 'branding']);
        } else {
            DB::table('settings')->insert([
                'key' => 'site_logo',
                'value' => null,
                'group' => 'branding',
                'type' => 'image',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Update site_favicon to group 'branding' if it exists, or create it
        $faviconExists = DB::table('settings')->where('key', 'site_favicon')->exists();
        if ($faviconExists) {
            DB::table('settings')->where('key', 'site_favicon')->update(['group' => 'branding']);
        } else {
            DB::table('settings')->insert([
                'key' => 'site_favicon',
                'value' => null,
                'group' => 'branding',
                'type' => 'image',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Insert branding settings
        $settings = [
            [
                'key' => 'site_logo_alt',
                'value' => 'BizScoop - High Integrity Business Journalism',
                'group' => 'branding',
                'type' => 'text',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'site_footer_logo',
                'value' => null,
                'group' => 'branding',
                'type' => 'image',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'site_footer_logo_alt',
                'value' => 'BizScoop - White Logo',
                'group' => 'branding',
                'type' => 'text',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        foreach ($settings as $setting) {
            $exists = DB::table('settings')->where('key', $setting['key'])->exists();
            if (!$exists) {
                DB::table('settings')->insert($setting);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', ['site_logo_alt', 'site_footer_logo', 'site_footer_logo_alt'])->delete();
        DB::table('settings')->where('key', 'site_logo')->update(['group' => 'general']);
        DB::table('settings')->where('key', 'site_favicon')->update(['group' => 'general']);
    }
};
