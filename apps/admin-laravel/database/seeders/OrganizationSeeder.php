<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'LinkedIn Learning',
                'website' => 'https://www.linkedin.com/learning/',
                'sort_order' => 0,
            ],
            [
                'name' => 'Udemy',
                'website' => 'https://www.udemy.com/',
                'sort_order' => 1,
            ],
        ];

        foreach ($items as $data) {
            Organization::updateOrCreate(
                ['name' => $data['name']],
                [
                    'website' => $data['website'] ?? null,
                    'sort_order' => $data['sort_order'] ?? 0,
                ]
            );
        }
    }
}
