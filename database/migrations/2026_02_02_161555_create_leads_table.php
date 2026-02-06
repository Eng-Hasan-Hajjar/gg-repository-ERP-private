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
        Schema::create('leads', function (Blueprint $table) {
            $table->id(); 
           
       $table->string('full_name');
      $table->string('phone')->nullable();
      $table->string('whatsapp')->nullable();
       $table->string('email')->nullable();
 $table->string('job')->nullable(); // العمل
      $table->date('first_contact_date')->nullable();
      $table->string('residence')->nullable();
      $table->unsignedTinyInteger('age')->nullable();
      $table->string('organization')->nullable();


      $table->enum('source', ['ad','referral','social','website','expo','other'])->default('other');
      $table->text('need')->nullable();

      $table->enum('stage', ['new','follow_up','interested','registered','rejected','postponed'])->default('new');
      $table->enum('registration_status', ['pending','converted','lost'])->default('pending');

      $table->date('registered_at')->nullable();
      $table->text('notes')->nullable();

      $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
      $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

      $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();



          $table->string('country')->nullable();
        $table->string('province')->nullable();
        $table->string('study')->nullable();


        
      $table->timestamps();
      $table->index(['branch_id','stage','registration_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
