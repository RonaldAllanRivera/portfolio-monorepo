<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed the initial Filament admin user
        $this->call(FilamentAdminSeeder::class);

        // Seed default issuing organizations for certifications
        $this->call(OrganizationSeeder::class);

        // Seed curated skills grouped by categories
        $this->call(SkillSeeder::class);

        // Seed certifications from embedded reviewed CSV
        $this->call(CertificationsEmbeddedSeeder::class);
    }
}
