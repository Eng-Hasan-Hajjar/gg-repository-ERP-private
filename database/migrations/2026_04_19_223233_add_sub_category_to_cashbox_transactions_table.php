<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('cashbox_transactions', function (Blueprint $table) {

            // التصنيف الثانوي (نصي حر)
            $table->string('sub_category')->nullable()->after('category');

            // حقول التصريف (صرف العملة)
            $table->string('foreign_currency', 3)->nullable()->after('currency');   // عملة أجنبية مثل TRY
            $table->decimal('foreign_amount', 12, 2)->nullable()->after('foreign_currency'); // المبلغ الأجنبي
$table->unsignedBigInteger('to_cashbox_id')->nullable()->after('cashbox_id');
            // تحديث enum لإضافة نوع exchange (تصريف)
            // ملاحظة: MySQL لا يدعم تعديل ENUM مباشرة لذا نستخدم هذه الطريقة
            \DB::statement("ALTER TABLE cashbox_transactions MODIFY COLUMN type ENUM('in','out','transfer','exchange')");

        });
    }

    public function down(): void
    {
        Schema::table('cashbox_transactions', function (Blueprint $table) {
            $table->dropColumn(['sub_category', 'foreign_currency', 'foreign_amount','to_cashbox_id']);
            \DB::statement("ALTER TABLE cashbox_transactions MODIFY COLUMN type ENUM('in','out','transfer')");
        });
    }
};