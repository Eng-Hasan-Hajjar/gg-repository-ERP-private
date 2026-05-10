<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });

        // قيم افتراضية
        DB::table('system_settings')->insert([
            ['key' => 'theme_mode',            'value' => 'light',  'group' => 'appearance'],
            ['key' => 'primary_color',         'value' => '#0ea5e9','group' => 'appearance'],
            ['key' => 'secondary_color',       'value' => '#10b981','group' => 'appearance'],
            ['key' => 'alert_followup_hours',  'value' => '48',     'group' => 'alerts'],
            ['key' => 'alert_warning_hours',   'value' => '24',     'group' => 'alerts'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};