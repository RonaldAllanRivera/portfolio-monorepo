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
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Basic info
            $table->string('title');
            $table->string('employment_type')->nullable(); // Full-time, Part-time, Contract, etc.
            $table->string('company_name');
            
            // Dates
            $table->boolean('is_current')->default(false);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            
            // Location
            $table->string('location')->nullable();
            $table->string('location_type')->nullable(); // On-site, Remote, Hybrid
            
            // Content
            $table->text('description')->nullable();
            $table->string('profile_headline')->nullable();
            
            // Skills are stored via experience_skill pivot
            
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
