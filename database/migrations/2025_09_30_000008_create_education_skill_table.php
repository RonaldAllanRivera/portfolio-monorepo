<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // In case a previous failed migration left a partial table behind
        Schema::dropIfExists('education_skill');

        Schema::create('education_skill', function (Blueprint $table) {
            $table->id();
            $table->foreignId('education_id')->constrained('educations')->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['education_id', 'skill_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('education_skill');
    }
};
