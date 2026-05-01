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
          $table->foreignId('exchange_to_cashbox_id')->nullable()->constrained('cashboxes')->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cashbox_transactions', function (Blueprint $table) {
$table->dropForeign(['exchange_to_cashbox_id']);
    $table->dropColumn('exchange_to_cashbox_id');
        });
    }
};
