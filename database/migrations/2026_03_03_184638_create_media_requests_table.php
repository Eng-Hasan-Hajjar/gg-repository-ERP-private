<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('media_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // مدير البرنامج
            $table->foreignId('diploma_id')->nullable()->constrained()->nullOnDelete();

          
            $table->text('description')->nullable();

            // Checkboxes تنفيذ
            $table->boolean('design_done')->default(false);
            $table->boolean('ad_done')->default(false);
            $table->boolean('invitation_done')->default(false);
            $table->boolean('content_done')->default(false);
            $table->boolean('podcast_done')->default(false);
            $table->boolean('reviews_done')->default(false);

            $table->text('notes')->nullable();






                  // بيانات مقدم الطلب
            $table->string('requester_name');
            $table->string('requester_phone')->nullable();

            // بيانات الدبلومة
            $table->string('diploma_name')->nullable();
            $table->string('diploma_code')->nullable();
            $table->string('trainer_name')->nullable();
            $table->string('trainer_location')->nullable();

            $table->boolean('trainer_photography_available')->default(false);

            $table->string('certificate_accreditation')->nullable();
            $table->string('customer_service_responsible')->nullable();
            $table->string('diploma_location')->nullable();

            // رفع ملفات
            $table->string('details_file')->nullable();
            $table->string('trainer_image')->nullable();

            // المواد المطلوبة (checkboxes)
            $table->boolean('need_ad')->default(false);
            $table->boolean('need_invitation')->default(false);
            $table->boolean('need_review_video')->default(false);
            $table->boolean('need_content')->default(false);
            $table->boolean('need_podcast')->default(false);
            $table->boolean('need_carousel')->default(false);
            $table->string('need_other')->nullable();





            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_requests');
    }
};
