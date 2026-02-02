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
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();

            $table->decimal('score', 6, 2)->nullable(); // الدرجة
            $table->enum('status', ['not_set','passed','failed','absent','excused'])->default('not_set');
            $table->text('notes')->nullable();

            // من قام بالإدخال (اختياري)
            $table->foreignId('entered_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->unique(['exam_id','student_id']);
            $table->index(['student_id','status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
