<?php
// database/migrations/xxxx_add_details_pdf_to_diplomas.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('diplomas', function (Blueprint $table) {
            $table->string('details_pdf')->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('diplomas', function (Blueprint $table) {
            $table->dropColumn('details_pdf');
        });
    }
};