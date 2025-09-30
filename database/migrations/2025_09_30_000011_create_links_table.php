<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('url');
            $table->string('type')->nullable(); // e.g., Repo, Live, Docs, Demo, Case Study, Other
            $table->timestamps();
            $table->softDeletes();
            $table->index('type');
            $table->index('url');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
