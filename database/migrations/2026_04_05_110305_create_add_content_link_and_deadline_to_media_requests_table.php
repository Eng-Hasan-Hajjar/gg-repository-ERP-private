<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('media_requests', function (Blueprint $table) {
            $table->string('content_link')->nullable()->after('notes');
            $table->date('editing_deadline')->nullable()->after('content_link');
        });
    }

    public function down(): void
    {
        Schema::table('media_requests', function (Blueprint $table) {
            $table->dropColumn(['content_link', 'editing_deadline']);
        });
    }
};