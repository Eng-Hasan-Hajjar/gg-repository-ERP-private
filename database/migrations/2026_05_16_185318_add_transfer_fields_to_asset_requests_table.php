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
    Schema::table('asset_requests', function (Blueprint $table) {
        $table->enum('status', [
            'pending','approved','rejected','transferred'
        ])->default('pending')->change();
        
        $table->unsignedBigInteger('transferred_to')->nullable();
        $table->unsignedBigInteger('transferred_by')->nullable();
        $table->timestamp('transferred_at')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_requests', function (Blueprint $table) {
                    $table->dropColumn('status');
        
        $table->dropColumn('transferred_to');
        $table->dropColumn('transferred_by');
        $table->dropColumn('transferred_at');

        });
    }
};
