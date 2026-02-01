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
        Schema::create('employee_schedules', function (Blueprint $table) {
            $table->id(); $table->foreignId('employee_id')->constrained()->cascadeOnDelete();

      $table->tinyInteger('weekday'); // 0 Sun .. 6 Sat
      $table->foreignId('work_shift_id')->nullable()->constrained()->nullOnDelete(); // null = Off

      $table->timestamps();
      $table->unique(['employee_id','weekday']);
      $table->index(['weekday','work_shift_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_schedules');
    }
};
