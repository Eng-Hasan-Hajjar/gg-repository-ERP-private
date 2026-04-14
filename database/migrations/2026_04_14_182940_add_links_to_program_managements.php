<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_managements', function (Blueprint $table) {
            $table->string('admin_session_1_link')->nullable()->after('admin_session_1');
            $table->string('admin_session_2_link')->nullable()->after('admin_session_2');
            $table->string('admin_session_3_link')->nullable()->after('admin_session_3');
            $table->string('evaluations_done_link')->nullable()->after('evaluations_done');
        });
    }

    public function down(): void
    {
        Schema::table('program_managements', function (Blueprint $table) {
            $table->dropColumn([
                'admin_session_1_link',
                'admin_session_2_link',
                'admin_session_3_link',
                'evaluations_done_link',
            ]);
        });
    }
};