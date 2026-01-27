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
        Schema::create('cashbox_transactions', function (Blueprint $table) {
            $table->id();
               $table->foreignId('cashbox_id')->constrained()->cascadeOnDelete();

      $table->date('trx_date');
      $table->enum('type',['in','out']); // مقبوض/مدفوع
      $table->decimal('amount', 12, 2);
      $table->string('currency',3); // نفس عملة الصندوق عادة

      $table->string('category')->nullable();  // مثال: راتب/قسط/مصاريف/إيجار...
      $table->string('reference')->nullable(); // رقم إيصال/مرجع خارجي
      $table->text('notes')->nullable();

      // حالة الترحيل
      $table->enum('status',['draft','posted'])->default('draft');
      $table->timestamp('posted_at')->nullable();

      // مرفق (صورة/ PDF)
      $table->string('attachment_path')->nullable();

      $table->timestamps();

      $table->index(['cashbox_id','trx_date']);
      $table->index(['type','status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashbox_transactions');
    }
};
