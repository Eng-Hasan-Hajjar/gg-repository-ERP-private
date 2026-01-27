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
        Schema::create('student_extra_fields', function (Blueprint $table) {
            $table->id();
                        
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();

            // مثال: العنوان، رقم جواز، تاريخ ميلاد، جهة عمل، ملاحظات...
            $table->json('data')->nullable();

         
            $table->unique('student_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_extra_fields');
    }
};
