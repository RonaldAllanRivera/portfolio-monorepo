<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Core content
            $table->string('headline')->nullable();
            $table->text('about_me')->nullable();

            // Media
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('profile_picture')->nullable();

            // SEO
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->json('seo_keywords')->nullable(); // stored as array of strings

            // Contact info
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_whatsapp')->nullable();

            // Social links
            $table->string('github_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('dribbble_url')->nullable();
            $table->string('behance_url')->nullable();

            // Personal info
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();           // e.g., Male, Female, Non-binary, Prefer not to say
            $table->string('marital_status')->nullable();   // e.g., Single, Married, etc.
            $table->string('nationality')->nullable();

            // Address (structured)
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_state')->nullable();
            $table->string('address_postal_code')->nullable();
            $table->string('address_country')->nullable();

            // Availability
            $table->boolean('open_to_work')->default(true);
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->json('preferred_roles')->nullable();

            // Ordering
            $table->integer('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'sort_order']);
            $table->index('gender');
            $table->index('marital_status');
            $table->index('nationality');
            $table->index('address_country');
            $table->index('open_to_work');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
