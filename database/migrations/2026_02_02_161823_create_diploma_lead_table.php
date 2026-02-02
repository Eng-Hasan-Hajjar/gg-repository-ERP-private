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
        Schema::create('diploma_lead', function (Blueprint $table) {
            $table->id();
             $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
      $table->foreignId('diploma_id')->constrained('diplomas')->cascadeOnDelete();
      $table->boolean('is_primary')->default(false);
      $table->timestamps();

      $table->unique(['lead_id','diploma_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diploma_lead');
    }
};
