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
        Schema::create('employee_contracts', function (Blueprint $table) {
            $table->id();

             $table->foreignId('employee_id')->constrained()->cascadeOnDelete();

            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('contract_type', ['full_time','part_time','freelance','hourly'])->default('freelance');

            $table->decimal('salary_amount', 12, 2)->nullable(); // إذا راتب ثابت
            $table->decimal('hour_rate', 12, 2)->nullable();     // إذا بالساعة
            $table->string('currency', 3)->default('USD');       // USD/TRY/EUR...

            $table->string('file_path')->nullable();             // ملف العقد PDF
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->index(['employee_id','contract_type']);

           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_contracts');
    }
};
