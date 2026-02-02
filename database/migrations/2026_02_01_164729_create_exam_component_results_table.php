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
        Schema::create('exam_component_results', function (Blueprint $table) {
            $table->id();   
            $table->foreignId('exam_component_id')->constrained('exam_components')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();

            $table->decimal('score', 6, 2)->nullable();
            $table->text('notes')->nullable();

            $table->foreignId('entered_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->unique(['exam_component_id','student_id']);
            $table->index(['student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_component_results');
    }
};
