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
        $smtpSettings = [
            [
                'key' => 'mail_mailer',
                'value' => env('MAIL_MAILER', 'smtp'),
                'group' => 'smtp',
                'type' => 'text',
            ],
            [
                'key' => 'mail_host',
                'value' => env('MAIL_HOST', 'sandbox.smtp.mailtrap.io'),
                'group' => 'smtp',
                'type' => 'text',
            ],
            [
                'key' => 'mail_port',
                'value' => env('MAIL_PORT', '2525'),
                'group' => 'smtp',
                'type' => 'text',
            ],
            [
                'key' => 'mail_username',
                'value' => env('MAIL_USERNAME', 'e61c53d272f5dd'),
                'group' => 'smtp',
                'type' => 'text',
            ],
            [
                'key' => 'mail_password',
                'value' => env('MAIL_PASSWORD', '96b6b4265b6d3e'),
                'group' => 'smtp',
                'type' => 'password',
            ],
            [
                'key' => 'mail_encryption',
                'value' => env('MAIL_ENCRYPTION', 'tls'),
                'group' => 'smtp',
                'type' => 'text',
            ],
            [
                'key' => 'mail_from_address',
                'value' => env('MAIL_FROM_ADDRESS', 'hello@bizscoop.com'),
                'group' => 'smtp',
                'type' => 'text',
            ],
            [
                'key' => 'mail_from_name',
                'value' => env('MAIL_FROM_NAME', 'BizScoop'),
                'group' => 'smtp',
                'type' => 'text',
            ],
        ];

        foreach ($smtpSettings as $setting) {
            $existing = DB::table('settings')->where('key', $setting['key'])->first();
            if ($existing) {
                // If it already exists, update its value using the env value if current database value is empty or default
                if (empty($existing->value) || $existing->value == 'smtp.mailtrap.io') {
                    DB::table('settings')->where('key', $setting['key'])->update([
                        'value' => $setting['value'],
                        'group' => 'smtp'
                    ]);
                } else {
                    DB::table('settings')->where('key', $setting['key'])->update([
                        'group' => 'smtp'
                    ]);
                }
            } else {
                DB::table('settings')->insert(array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'mail_mailer',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name'
        ])->delete();
    }
};
