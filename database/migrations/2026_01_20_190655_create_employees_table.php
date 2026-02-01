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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
           

            
            $table->string('code')->unique(); // EMP-2026-XXXX
            $table->string('full_name');
            $table->enum('type', ['trainer','employee'])->default('trainer'); // مدرب/موظف

            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete(); // اختياري حسب الفروع
            $table->string('job_title')->nullable(); // المسمى الوظيفي
            $table->enum('status', ['active','inactive'])->default('active');

            $table->text('notes')->nullable();

 $table->enum('schedule_mode', ['weekly','custom'])->default('weekly');
    $table->date('schedule_effective_from')->nullable();

            $table->timestamps();


               $table->index(['type','status']);
            $table->index(['branch_id']);

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
