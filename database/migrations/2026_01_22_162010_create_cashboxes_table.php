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
        Schema::create('cashboxes', function (Blueprint $table) {
            $table->id();
                  $table->string('name');                // صندوق اسطنبول - USD مثلاً
      $table->string('code')->unique();      // CB-IST-USD
      $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();

      $table->string('currency',3)->default('USD'); // USD/TRY/EUR
      $table->enum('status',['active','inactive'])->default('active');

      // رصيد افتتاحي (اختياري)
      $table->decimal('opening_balance', 12, 2)->default(0);

      $table->timestamps();

      $table->index(['branch_id','currency','status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashboxes');
    }
};
