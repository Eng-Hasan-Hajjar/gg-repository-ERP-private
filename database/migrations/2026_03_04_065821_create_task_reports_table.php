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
        Schema::create('task_reports', function (Blueprint $table) {
            $table->id();


                 $table->foreignId('employee_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('task_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->enum('report_type',[
                'daily',
                'weekly',
                'monthly'
            ]);

            $table->date('report_date');

            $table->string('title')->nullable();

            $table->text('notes')->nullable();

            $table->string('file_path')->nullable();

            $table->timestamps();

            $table->unique([
                'employee_id',
                'report_type',
                'report_date'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_reports');
    }
};
