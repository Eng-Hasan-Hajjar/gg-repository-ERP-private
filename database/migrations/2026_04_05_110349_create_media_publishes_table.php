<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('media_publishes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('media_request_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->string('diploma_name');

            $table->enum('content_category', [
                'ad',
                'invitation',
                'content',
                'review',
                'general_content',
            ]);

            $table->enum('content_type', [
                'design',
                'video',
                'carousel',
            ]);

            $table->string('branch')->nullable();
            $table->text('caption')->nullable();

            $table->boolean('published_meta')->default(false);
            $table->boolean('published_tiktok')->default(false);
            $table->boolean('published_youtube')->default(false);

            $table->date('publish_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_publishes');
    }
};