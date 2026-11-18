<?php

namespace Database\Seeders;

use App\Models\PrimeExpert;
use App\Services\PrimeAssignmentSetupService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Imports Prime writer profiles from database/seeders/data/prime_writers.json
 * (source: "UK Writer Profiles — 100 Writers (13 Categories)" document).
 *
 * Safe to run multiple times: writers are matched by slug and updated, never duplicated.
 * Run: php artisan db:seed --class=PrimeExpertSeeder
 */
class PrimeExpertSeeder extends Seeder
{
    public function run(): void
    {
        PrimeAssignmentSetupService::ensureSetup();

        $writers = json_decode(file_get_contents(database_path('seeders/data/prime_writers.json')), true);
        $created = 0;
        $updated = 0;

        foreach ($writers as $w) {
            $slug = Str::slug($w['name']);
            $categoryShort = trim(str_ireplace('Assignment Help', '', $w['category']));

            $expert = PrimeExpert::firstOrNew(['slug' => $slug]);
            $isNew = !$expert->exists;

            $expert->fill([
                'name'             => $w['name'],
                'image_alt'        => $w['name'] . ' - ' . $categoryShort . ' Assignment Writer',
                'gender'           => $w['gender'],
                'degree'           => $w['degree'],
                'experience'       => $w['experience'],
                'finish_order'     => (string) $w['completed_orders'],
                'rating'           => $w['rating'],
                'subject'          => $w['category'],
                'location'         => $w['location'],
                'content'          => '<p>' . e($w['bio']) . '</p>',
                'skills'           => $w['skills'],
                'meta_tag'         => Str::limit($w['name'] . ' | ' . $categoryShort . ' Assignment Writer UK', 255, ''),
                'meta_description' => Str::limit($w['bio'], 155),
            ]);

            // Keep admin-entered values (image, reviews, etc.) on re-run; only default them for new rows.
            if ($isNew) {
                $expert->helpus = [];
                $expert->customer_review = [];
                $expert->is_active = true;
            }

            $expert->save();
            $isNew ? $created++ : $updated++;
        }

        $this->command?->info("Prime writers imported: {$created} created, {$updated} updated.");
    }
}
