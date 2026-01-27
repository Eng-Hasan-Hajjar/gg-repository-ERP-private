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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            
            $table->string('asset_tag')->unique(); // AST-2026-XXXX
            $table->string('name');
            $table->text('description')->nullable();

            $table->foreignId('asset_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('condition', ['good','maintenance','out_of_service'])->default('good');
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_cost', 12, 2)->nullable();
            $table->string('currency', 3)->default('USD');

            $table->string('serial_number')->nullable();
            $table->string('location')->nullable(); // غرفة/مخزن/قاعة

            $table->string('photo_path')->nullable(); // صورة
            $table->timestamps();

            $table->index(['branch_id','condition']);
            $table->index(['asset_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
