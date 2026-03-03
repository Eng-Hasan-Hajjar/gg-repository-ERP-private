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
        Schema::create('media_schedules', function (Blueprint $table) {
            $table->id();

             $table->foreignId('media_request_id')->constrained()->cascadeOnDelete();

            $table->string('title');

            $table->enum('post_type', [
                'design',
                'ad_video',
                'content_video',
                'reviews',
                'invitation',
                'general_content'
            ]);

            $table->dateTime('publish_at')->nullable();
            $table->string('content_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_schedules');
    }
};
