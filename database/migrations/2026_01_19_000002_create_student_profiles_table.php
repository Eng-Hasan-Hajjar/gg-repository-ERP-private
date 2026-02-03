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



        // ✅ بيانات تفصيلية (بدون تكرار غير ضروري)
      $table->string('level')->nullable(); // المستوى
      $table->string('stage_in_state')->nullable(); // ستاج/مرحلة بالولاية
      $table->string('job')->nullable(); // العمل
      $table->string('education_level')->nullable(); // المستوى التعليمي

      // ✅ رسالة لاحقة للطالب
      $table->text('message_to_send')->nullable();

      // ✅ وثائق إضافية
      $table->string('attendance_certificate_path')->nullable(); // شهادة حضور
      $table->string('certificate_card_path')->nullable(); // شهادة كرتون
 



      $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
