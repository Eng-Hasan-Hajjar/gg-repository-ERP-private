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
        Schema::table('diploma_lead', function (Blueprint $table) {
            $table->boolean('has_grant')->default(false)->after('is_primary');
            $table->text('grant_details')->nullable()->after('has_grant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diploma_lead', function (Blueprint $table) {
            $table->dropColumn(['has_grant', 'grant_details']);
        });
    }
};