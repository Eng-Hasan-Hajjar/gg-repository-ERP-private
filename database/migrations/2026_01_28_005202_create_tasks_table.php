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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();    $table->string('title');
      $table->text('description')->nullable();

      $table->foreignId('assigned_to')->nullable()->constrained('employees')->nullOnDelete();
      $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();

      $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

      $table->enum('priority',['low','medium','high','urgent'])->default('medium');
      $table->enum('status',['todo','in_progress','done','blocked','archived'])->default('todo');

      $table->date('due_date')->nullable();
      $table->timestamp('completed_at')->nullable();

      $table->timestamps();
      $table->index(['status','priority']);
      $table->index(['branch_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
