<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('media_publishes', function (Blueprint $table) {
            $table->string('content_link')->nullable()->after('caption');
            $table->string('branch_link')->nullable()->after('content_link');
        });
    }

    public function down(): void
    {
        Schema::table('media_publishes', function (Blueprint $table) {
            $table->dropColumn(['content_link', 'branch_link']);
        });
    }
};