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
        Schema::create('employee_schedule_overrides', function (Blueprint $table) {
            $table->id();
              $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
    $table->date('work_date');
    $table->foreignId('work_shift_id')->nullable()->constrained()->nullOnDelete(); // null = OFF
    $table->string('reason')->nullable(); // سبب التغيير
    $table->timestamps();

    $table->unique(['employee_id','work_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_schedule_overrides');
    }
};
