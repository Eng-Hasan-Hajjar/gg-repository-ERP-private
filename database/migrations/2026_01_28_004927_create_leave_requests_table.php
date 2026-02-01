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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();$table->foreignId('employee_id')->constrained()->cascadeOnDelete();

      $table->enum('type',['leave','permission'])->default('leave'); // إجازة/إذن
      $table->date('start_date');
      $table->date('end_date')->nullable(); // إذن ممكن يوم واحد
      $table->text('reason')->nullable();

      $table->enum('status',['pending','approved','rejected'])->default('pending');
      $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
      $table->timestamp('approved_at')->nullable();
      $table->text('admin_note')->nullable();

      $table->timestamps();
      $table->index(['employee_id','status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
