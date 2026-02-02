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
        Schema::create('exam_components', function (Blueprint $table) {
            $table->id();    $table->foreignId('exam_id')->constrained()->cascadeOnDelete();

            $table->string('title'); // عملي 1 / مشروع / ...
            $table->string('key')->nullable(); // optional key like practical1
            $table->decimal('max_score', 6, 2)->default(100);
            $table->decimal('weight', 6, 2)->default(0); // percent-like (0..100)
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['exam_id','sort_order']);
            $table->unique(['exam_id','key']); // if key provided, keep unique per exam
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_components');
    }
};
