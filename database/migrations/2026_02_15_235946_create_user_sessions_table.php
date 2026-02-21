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
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
          
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();

    $table->timestamp('login_at')->nullable();
    $table->timestamp('last_activity')->nullable();
    $table->timestamp('logout_at')->nullable();

    $table->string('session_id')->nullable()->index();
    $table->string('ip')->nullable();
    $table->string('user_agent')->nullable();

    $table->date('work_date'); // لاحتساب ساعات كل يوم

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};
