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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            
            // رقم جامعي/عضوية
            $table->string('university_id')->unique();

            // الاسم
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name');

            // تواصل
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable(); // رابط أو رقم

            // فرع + نوع الطالب (حضوري/أونلاين)
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();

                                // ربط الدبلومة (اختياري حسب وجودها)
            $table->foreignId('diploma_id')->nullable()->constrained()->nullOnDelete();


            $table->enum('mode', ['onsite', 'online'])->default('onsite');

            // حالة الطالب (حسب وصفك)
            $table->enum('status', [
                'active', 'waiting', 'paid', 'withdrawn', 'failed', 'absent_exam',
                'certificate_delivered', 'certificate_waiting', 'registration_ended',
                'dismissed', 'frozen'
            ])->default('waiting');

            // تثبيت الطالب
            $table->boolean('is_confirmed')->default(false);
            $table->timestamp('confirmed_at')->nullable();



            // إيميل (مفيد حتى في المرحلة الأولية)
            $table->string('email')->nullable();

            // حالة التسجيل (غير حالة الطالب الأكاديمية)
            $table->enum('registration_status', ['pending','confirmed','archived','dismissed','frozen'])
                ->default('pending')
                ;

            // فهرسة
            $table->index(['branch_id', 'status']);
            $table->index(['registration_status']);
         



    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
