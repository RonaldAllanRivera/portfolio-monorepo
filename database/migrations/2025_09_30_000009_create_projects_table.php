<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('experience_id')->nullable()->constrained()->nullOnDelete();

            // Basic
            $table->string('name');
            $table->text('description')->nullable();

            // Dates
            $table->boolean('is_current')->default(false);
            $table->date('start_date');
            $table->date('end_date')->nullable();

            // Media (JSON array of file paths/URLs)
            $table->json('media')->nullable();

            // Ordering
            $table->integer('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['user_id', 'sort_order']);
            $table->index('is_current');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
