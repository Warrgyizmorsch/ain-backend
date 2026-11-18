<?php

namespace Database\Seeders;

use App\Models\PrimeReview;
use App\Services\PrimeAssignmentSetupService;
use Illuminate\Database\Seeder;

/**
 * Imports Prime testimonials from database/seeders/data/prime_reviews.json
 * (source: "Homepage testimonials" document — 126 testimonials across 21 website pages).
 *
 * Safe to run multiple times: a review is matched by page + name and updated, never duplicated.
 * Run: php artisan db:seed --class=PrimeReviewSeeder
 */
class PrimeReviewSeeder extends Seeder
{
    public function run(): void
    {
        PrimeAssignmentSetupService::ensureSetup();

        $reviews = json_decode(file_get_contents(database_path('seeders/data/prime_reviews.json')), true);
        $created = 0;
        $updated = 0;

        foreach ($reviews as $r) {
            $review = PrimeReview::firstOrNew(['page' => $r['page'], 'name' => $r['name']]);
            $isNew = !$review->exists;

            $review->fill([
                'university'    => $r['university'],
                'course'        => $r['course'],
                'location'      => $r['location'],
                'services_type' => $r['services_type'],
                'description'   => $r['description'],
            ]);

            // The document has no star ratings: new testimonials default to 5, and a rating
            // changed later in admin is kept on re-run.
            if ($isNew) {
                $review->customer_rating = 5;
                $review->is_active = true;
            }

            $review->save();
            $isNew ? $created++ : $updated++;
        }

        $this->command?->info("Prime reviews imported: {$created} created, {$updated} updated.");
    }
}
