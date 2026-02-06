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
        
         $table->string('university_id')->unique();

      $table->string('first_name');
      $table->string('last_name')->default('-');
      $table->string('full_name');

      $table->string('phone')->nullable();
      $table->string('whatsapp')->nullable();
     

      $table->foreignId('branch_id')->constrained()->cascadeOnDelete();

      $table->enum('mode', ['onsite','online'])->default('onsite');

      $table->enum('status', [
        'active','waiting','paid','withdrawn','failed','absent_exam',
        'certificate_delivered','certificate_waiting','registration_ended',
        'dismissed','frozen'
      ])->default('waiting');

      $table->enum('registration_status', ['pending','confirmed','archived','dismissed','frozen'])
        ->default('confirmed');

      $table->boolean('is_confirmed')->default(true);
      $table->timestamp('confirmed_at')->nullable();

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
