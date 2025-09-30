<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('experiences', 'skills')) {
            Schema::table('experiences', function (Blueprint $table) {
                $table->dropColumn('skills');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('experiences', 'skills')) {
            Schema::table('experiences', function (Blueprint $table) {
                $table->json('skills')->nullable();
            });
        }
    }
};
