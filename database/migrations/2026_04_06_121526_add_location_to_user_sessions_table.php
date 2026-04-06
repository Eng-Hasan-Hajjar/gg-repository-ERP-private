// database/migrations/xxxx_add_location_to_user_sessions_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_sessions', function (Blueprint $table) {
            $table->string('country')->nullable()->after('ip');
            $table->string('city')->nullable()->after('country');
            $table->string('latitude', 20)->nullable()->after('city');
            $table->string('longitude', 20)->nullable()->after('latitude');
            $table->string('address_detail', 500)->nullable()->after('city');
        });
    }

    public function down(): void
    {
        Schema::table('user_sessions', function (Blueprint $table) {
            $table->dropColumn(['country', 'city', 'latitude', 'longitude']);
        });
    }
};