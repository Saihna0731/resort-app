<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResortSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! Schema::hasTable('resorts')) {
            $this->command?->warn("Table 'resorts' does not exist. Skipping ResortSeeder.");
            $this->command?->line("Tip: create a resorts migration first, then re-run this seeder.");
            return;
        }

        $columns = Schema::getColumnListing('resorts');

        $candidates = [
            [
                'name' => 'Sunset Bay Resort',
                'location' => 'Ulaanbaatar',
                'region' => 'Улаанбаатар',
                'description' => 'A comfortable place to stay.',
                'price_per_night' => 120000,
            ],
            [
                'name' => 'Mountain View Lodge',
                'location' => 'Terelj',
                'region' => 'Тэрэлж',
                'description' => 'Quiet lodge with great views.',
                'price_per_night' => 180000,
            ],
        ];

        $rows = [];
        foreach ($candidates as $candidate) {
            $row = [];
            foreach ($candidate as $key => $value) {
                if (in_array($key, $columns, true)) {
                    $row[$key] = $value;
                }
            }

            $now = now();
            if (in_array('created_at', $columns, true)) {
                $row['created_at'] = $now;
            }
            if (in_array('updated_at', $columns, true)) {
                $row['updated_at'] = $now;
            }

            if (! empty($row)) {
                $rows[] = $row;
            }
        }

        if (empty($rows)) {
            $this->command?->warn("Table 'resorts' exists, but no known columns matched. Skipping insert.");
            $this->command?->line('Update ResortSeeder to match your resorts table columns.');
            return;
        }

        $now = now();
        foreach ($rows as $row) {
            $keys = [];
            if (array_key_exists('name', $row)) {
                $keys['name'] = $row['name'];
            }
            if (array_key_exists('location', $row)) {
                $keys['location'] = $row['location'];
            }

            if (empty($keys)) {
                DB::table('resorts')->insert($row);
                continue;
            }

            if (in_array('updated_at', $columns, true) && ! array_key_exists('updated_at', $row)) {
                $row['updated_at'] = $now;
            }
            if (in_array('created_at', $columns, true) && ! array_key_exists('created_at', $row)) {
                $row['created_at'] = $now;
            }

            DB::table('resorts')->updateOrInsert($keys, $row);
        }

        $this->command?->info('Resorts seeded successfully (no duplicates).');
    }
}
