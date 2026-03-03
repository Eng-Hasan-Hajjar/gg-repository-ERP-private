<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('media_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // مدير البرنامج
            $table->foreignId('diploma_id')->nullable()->constrained()->nullOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            // Checkboxes تنفيذ
            $table->boolean('design_done')->default(false);
            $table->boolean('ad_done')->default(false);
            $table->boolean('invitation_done')->default(false);
            $table->boolean('content_done')->default(false);
            $table->boolean('podcast_done')->default(false);
            $table->boolean('reviews_done')->default(false);

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_requests');
    }
};
