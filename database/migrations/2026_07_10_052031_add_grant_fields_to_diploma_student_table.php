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
        Schema::table('diploma_student', function (Blueprint $table) {
            $table->boolean('has_grant')->default(false)->after('certificate_delivered');
            $table->text('grant_details')->nullable()->after('has_grant');
            $table->boolean('grant_given')->default(false)->after('grant_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diploma_student', function (Blueprint $table) {
            $table->dropColumn(['has_grant', 'grant_details', 'grant_given']);
        });
    }
};