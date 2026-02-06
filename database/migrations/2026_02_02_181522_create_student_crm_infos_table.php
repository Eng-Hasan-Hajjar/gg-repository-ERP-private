<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_crm_infos', function (Blueprint $table) {
            $table->id();

      $table->foreignId('student_id')->unique()->constrained()->cascadeOnDelete();

      $table->date('first_contact_date')->nullable();
      $table->string('residence')->nullable();
    $table->string('email')->nullable();
     $table->string('job')->nullable(); // العمل
      $table->unsignedTinyInteger('age')->nullable();
      $table->string('organization')->nullable();

      $table->enum('source', ['ad','referral','social','website','expo','other'])->default('other');
      $table->text('need')->nullable();

      $table->enum('stage', ['new','follow_up','interested','registered','rejected','postponed'])->default('registered');
      $table->timestamp('converted_at')->nullable();

      $table->text('notes')->nullable();


              $table->string('country')->nullable();
        $table->string('province')->nullable();
        $table->string('study')->nullable();
        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();


        
      $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_crm_infos');
    }
};
