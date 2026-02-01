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
        Schema::create('work_shifts', function (Blueprint $table) {
            $table->id();
              $table->string('name');              // صباحي / مسائي / اونلاين ...
      $table->string('code')->unique();    // AM / PM / ONL ...
      $table->time('start_time');
      $table->time('end_time');
      $table->unsignedSmallInteger('grace_minutes')->default(10); // سماح تأخير
      $table->boolean('is_active')->default(true);
      $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_shifts');
    }
};
