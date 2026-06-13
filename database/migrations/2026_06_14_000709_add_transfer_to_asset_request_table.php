<?php
// database/migrations/2026_06_13_000001_add_transfer_to_asset_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_requests', function (Blueprint $table) {
            // تغيير enum ليشمل transfer
            $table->enum('type', ['purchase', 'repair', 'transfer'])
                  ->default('purchase')
                  ->change();

            // فروع النقل
            $table->foreignId('from_branch_id')
                  ->nullable()
                  ->after('branch_id')
                  ->constrained('branches')
                  ->nullOnDelete();

            $table->foreignId('to_branch_id')
                  ->nullable()
                  ->after('from_branch_id')
                  ->constrained('branches')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('asset_requests', function (Blueprint $table) {
            $table->dropForeign(['from_branch_id']);
            $table->dropForeign(['to_branch_id']);
            $table->dropColumn(['from_branch_id', 'to_branch_id']);

            $table->enum('type', ['purchase', 'repair'])
                  ->default('purchase')
                  ->change();
        });
    }
};