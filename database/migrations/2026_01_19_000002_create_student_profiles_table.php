<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->unique('student_id');

            // معلومات شخصية
            $table->string('arabic_full_name')->nullable();       // الاسم بالعربي
            $table->string('national_id')->nullable();            // الرقم الوطني
            $table->date('birth_date')->nullable();               // تاريخ التولد
            $table->string('nationality')->nullable();            // الجنسية

            // تواصل/موقع
            $table->string('address')->nullable();
            $table->string('location')->nullable();

            // تعليم/عمل/مرحلة
            $table->string('stage')->nullable();                  // ستاج
            $table->string('work')->nullable();                   // العمل
            $table->string('education_level')->nullable();        // المستوى التعليمي

            // نتائج/ملاحظات
            $table->decimal('exam_score', 5, 2)->nullable();       // العلامة الامتحانية
            $table->text('notes')->nullable();                    // ملاحظات

            // ملفات
            $table->string('photo_path')->nullable();             // صورة الطالب
            $table->string('info_file_path')->nullable();         // رابط ملف المعلومات/ملف
            $table->string('identity_file_path')->nullable();     // ملف الهوية

            // شهادات (حالة + ملفات)
            $table->boolean('has_attendance_certificate')->default(false);
            $table->boolean('has_certificate_pdf')->default(false);
            $table->string('certificate_pdf_path')->nullable();
            $table->boolean('has_certificate_card')->default(false);

            // رسالة لاحقة
            $table->text('message_to_student')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
