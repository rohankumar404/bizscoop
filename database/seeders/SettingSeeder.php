<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name', 'value' => 'BizScoop', 'group' => 'general', 'type' => 'text'],
            ['key' => 'site_tagline', 'value' => 'The Future of Business Journalism', 'group' => 'general', 'type' => 'text'],
            
            // Branding & Logos
            ['key' => 'site_logo', 'value' => null, 'group' => 'branding', 'type' => 'image'],
            ['key' => 'site_logo_alt', 'value' => 'BizScoop - High Integrity Business Journalism', 'group' => 'branding', 'type' => 'text'],
            ['key' => 'site_footer_logo', 'value' => null, 'group' => 'branding', 'type' => 'image'],
            ['key' => 'site_footer_logo_alt', 'value' => 'BizScoop - White Logo', 'group' => 'branding', 'type' => 'text'],
            ['key' => 'site_favicon', 'value' => null, 'group' => 'branding', 'type' => 'image'],
            
            // SEO
            ['key' => 'default_meta_title', 'value' => 'BizScoop | High Integrity Business Journalism', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'default_meta_description', 'value' => 'Delivering high-integrity journalism for the modern professional.', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'default_meta_keywords', 'value' => 'business, news, finance, markets, journalism', 'group' => 'seo', 'type' => 'text'],
            
            // Social Media
            ['key' => 'social_twitter', 'value' => 'https://twitter.com/bizscoop', 'group' => 'social', 'type' => 'text'],
            ['key' => 'social_linkedin', 'value' => 'https://linkedin.com/company/bizscoop', 'group' => 'social', 'type' => 'text'],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/bizscoop', 'group' => 'social', 'type' => 'text'],
            
            // SMTP
            ['key' => 'mail_host', 'value' => 'smtp.mailtrap.io', 'group' => 'smtp', 'type' => 'text'],
            ['key' => 'mail_port', 'value' => '2525', 'group' => 'smtp', 'type' => 'text'],
            ['key' => 'mail_username', 'value' => '', 'group' => 'smtp', 'type' => 'text'],
            ['key' => 'mail_password', 'value' => '', 'group' => 'smtp', 'type' => 'password'],
            
            // Analytics & Scripts
            ['key' => 'analytics_ga_id', 'value' => '', 'group' => 'scripts', 'type' => 'text'],
            ['key' => 'custom_header_code', 'value' => '', 'group' => 'scripts', 'type' => 'textarea'],
            ['key' => 'custom_footer_code', 'value' => '', 'group' => 'scripts', 'type' => 'textarea'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
