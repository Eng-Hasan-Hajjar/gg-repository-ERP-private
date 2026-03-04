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
        Schema::create('program_managements', function (Blueprint $table) {
            $table->id();
               $table->foreignId('diploma_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('manager_id') // مدير البرنامج
                ->constrained('users')
                ->cascadeOnDelete();



            $table->unsignedBigInteger('trainer_id')->nullable();

            $table->string('communication_manager')->nullable();

        

            $table->foreign('trainer_id')
                ->references('id')
                ->on('employees')
                ->nullOnDelete();


            // ===== قسم البرامج =====
            $table->boolean('market_study')->default(false);
            $table->boolean('trainer_assigned')->default(false);
            $table->boolean('contracts_ready')->default(false);
            $table->boolean('materials_ready')->default(false);
            $table->boolean('sessions_uploaded')->default(false);
            $table->string('certificate_source')->nullable();
            $table->string('details_file')->nullable();
              
            $table->decimal('price',10,2)->nullable();

            // ===== قسم الميديا =====
            $table->boolean('media_form_sent')->default(false);
            $table->boolean('direct_ads')->default(false);
            $table->boolean('content_ready')->default(false);
            $table->boolean('opening_invitation')->default(false);
            $table->boolean('opening_snippets')->default(false);
            $table->boolean('carousel')->default(false);
            $table->boolean('designs')->default(false);
            $table->boolean('stories')->default(false);

            // ===== التسويق =====
            $table->date('campaign_start')->nullable();
            $table->date('campaign_end')->nullable();
            $table->decimal('campaign_budget',10,2)->nullable();

            // ===== الامتحانات =====
            $table->integer('confirmed_students')->nullable();
            $table->integer('duration_months')->nullable();
            $table->integer('hours')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('mid_exam')->nullable();
            $table->date('final_exam')->nullable();
            $table->boolean('projects')->default(false);

            // ===== شؤون الطلاب =====
            $table->boolean('attendance_certificate')->default(false);
            $table->boolean('university_certificate')->default(false);
            $table->boolean('cards_ready')->default(false);
            $table->boolean('admin_session_1')->default(false);
            $table->boolean('admin_session_2')->default(false);
            $table->boolean('admin_session_3')->default(false);
            $table->boolean('evaluations_done')->default(false);
            $table->integer('graduates_count')->nullable();

            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_managements');
    }
};
