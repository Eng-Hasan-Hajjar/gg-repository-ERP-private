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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // اسم الامتحان
            $table->string('code')->nullable()->unique(); // كود اختياري
            $table->date('exam_date')->nullable();
            $table->enum('type', ['quiz','midterm','final','practical','other'])->default('other');

            $table->decimal('max_score', 6, 2)->default(100);
            $table->decimal('pass_score', 6, 2)->nullable();

            $table->foreignId('diploma_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();

            // المدرب/الموظف المسؤول (اختياري)
            $table->foreignId('trainer_id')->nullable()->constrained('employees')->nullOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['diploma_id','branch_id','exam_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
