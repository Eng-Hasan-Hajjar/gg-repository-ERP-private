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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();$table->foreignId('employee_id')->constrained()->cascadeOnDelete();
      $table->date('work_date'); // يوم الدوام

      $table->foreignId('work_shift_id')->nullable()->constrained()->nullOnDelete(); // شيفت هذا اليوم
      $table->timestamp('check_in_at')->nullable();
      $table->timestamp('check_out_at')->nullable();

      $table->unsignedInteger('late_minutes')->default(0);
      $table->unsignedInteger('worked_minutes')->default(0);

      $table->enum('status', ['scheduled','present','late','absent','off','leave'])->default('scheduled');
      $table->text('notes')->nullable();

      $table->timestamps();
      $table->unique(['employee_id','work_date']);
      $table->index(['work_date','status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
