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
        Schema::table('payment_plans', function (Blueprint $table) {
              $table->foreignId('lead_id')
            ->nullable()          // Existing rows won't fail
           
            ->constrained()
            ->cascadeOnDelete();
        });


        // ── الخطوة 2: جعل student_id nullable ──
        // نستخدم raw SQL لأن change() يحتاج doctrine/dbal
        DB::statement('ALTER TABLE payment_plans MODIFY student_id BIGINT UNSIGNED NULL');


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_plans', function (Blueprint $table) {
            //
        });
              // إعادة student_id إلى NOT NULL (تأكد أنه لا يوجد NULL قبل هذا)
        DB::statement('ALTER TABLE payment_plans MODIFY student_id BIGINT UNSIGNED NOT NULL');
    }
};
