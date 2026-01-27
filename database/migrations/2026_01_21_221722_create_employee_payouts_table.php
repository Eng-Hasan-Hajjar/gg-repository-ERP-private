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
        Schema::create('employee_payouts', function (Blueprint $table) {
            $table->id();
             $table->foreignId('employee_id')->constrained()->cascadeOnDelete();

            $table->date('payout_date');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');

            $table->enum('status', ['pending','paid'])->default('pending');
            $table->string('reference')->nullable(); // رقم إيصال/مرجع
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->index(['employee_id','status','payout_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_payouts');
    }
};
