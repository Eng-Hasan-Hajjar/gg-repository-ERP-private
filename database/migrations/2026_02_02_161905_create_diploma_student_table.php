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
        Schema::create('diploma_student', function (Blueprint $table) {
            $table->id();   
               $table->foreignId('student_id')->constrained()->cascadeOnDelete();
      $table->foreignId('diploma_id')->constrained()->cascadeOnDelete();

      $table->boolean('is_primary')->default(false);
      $table->date('enrolled_at')->nullable();
      $table->string('status')->default('active');
      $table->text('notes')->nullable();



        $table->boolean('has_attendance_certificate')->default(false);
    $table->string('attendance_certificate_path')->nullable();
    $table->string('certificate_pdf_path')->nullable();
    $table->string('certificate_card_path')->nullable();




        $table->tinyInteger('rating')->nullable(); // التقييم 1-5
    
    $table->date('ended_at')->nullable(); // تاريخ انتهاء الدبلومة
    $table->boolean('certificate_delivered')->default(false); // هل سُلّمت الشهادة؟




      $table->timestamps();
      $table->unique(['student_id','diploma_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diploma_student');
    }
};
