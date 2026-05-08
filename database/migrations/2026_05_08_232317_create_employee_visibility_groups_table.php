<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // جدول المجموعات
        Schema::create('visibility_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // مثال: "برامج الأونلاين", "فرع إسطنبول"
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // الموظفون المنضمون لكل مجموعة (يرون تقارير بعضهم)
        Schema::create('visibility_group_employee', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visibility_group_id')->constrained('visibility_groups')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->enum('role_in_group', ['manager', 'member'])->default('member');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visibility_group_employee');
        Schema::dropIfExists('visibility_groups');
    }
};