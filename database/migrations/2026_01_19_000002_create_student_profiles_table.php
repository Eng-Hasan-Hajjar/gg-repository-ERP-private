<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();

        $table->foreignId('student_id')->unique()->constrained()->cascadeOnDelete();

      $table->string('arabic_full_name')->nullable();
      $table->string('nationality')->nullable();
      $table->date('birth_date')->nullable();
      $table->string('national_id')->nullable();
      $table->string('address')->nullable();

      $table->string('photo_path')->nullable();
      $table->string('info_file_path')->nullable();
      $table->string('identity_file_path')->nullable();
      $table->string('certificate_pdf_path')->nullable();

      $table->decimal('exam_score', 6, 2)->nullable();
      $table->text('notes')->nullable();

      $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
