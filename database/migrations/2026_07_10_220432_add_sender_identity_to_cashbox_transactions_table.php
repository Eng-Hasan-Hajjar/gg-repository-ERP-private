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
        Schema::table('cashbox_transactions', function (Blueprint $table) {
            // صورة هوية مرسل الحوالة (وصل الحوالة يُخزَّن في attachment_path الموجود مسبقاً)
            $table->string('sender_identity_path')->nullable()->after('attachment_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cashbox_transactions', function (Blueprint $table) {
            $table->dropColumn('sender_identity_path');
        });
    }
};