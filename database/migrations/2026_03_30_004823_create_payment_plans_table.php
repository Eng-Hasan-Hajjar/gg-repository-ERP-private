<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_plans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();
           
            $table->foreignId('diploma_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('total_amount', 10, 2);

            $table->enum('payment_type', ['full', 'installments']);

            $table->integer('installments_count')->nullable();

            $table->string('currency', 10)->default('USD');


            $table->timestamps();

            $table->unique(['student_id','diploma_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_plans');
    }
};
