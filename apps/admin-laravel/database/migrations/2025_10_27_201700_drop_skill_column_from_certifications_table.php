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
        if (Schema::hasTable('certifications') && Schema::hasColumn('certifications', 'skill')) {
            Schema::table('certifications', function (Blueprint $table) {
                $table->dropColumn('skill');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('certifications') && ! Schema::hasColumn('certifications', 'skill')) {
            Schema::table('certifications', function (Blueprint $table) {
                // Recreate legacy column as nullable text and keep placement close to original fields
                $table->text('skill')->nullable()->after('credential_url');
            });
        }
    }
};
