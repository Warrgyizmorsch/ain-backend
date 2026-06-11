<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppDropdownController extends Controller
{
    public function countries()
    {
        $countries = DB::table('countries')
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $countries
        ]);
    }

    public function services()
    {
        $services = [
            [
                'id' => 1,
                'name' => 'Assignment',
                'value' => 'Assignment',
                'multiplier' => 1
            ],
            [
                'id' => 2,
                'name' => 'Dissertation',
                'value' => 'Dissertation',
                'multiplier' => 1.1
            ],
            [
                'id' => 3,
                'name' => 'Thesis',
                'value' => 'Thesis',
                'multiplier' => 1.1
            ],
            [
                'id' => 4,
                'name' => 'Research Project',
                'value' => 'Research Project',
                'multiplier' => 1.1
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    public function subjects()
    {
        $subjects = [
            ['id' => 1, 'name' => 'Matlab', 'value' => 'Matlab'],
            ['id' => 2, 'name' => 'Data Science', 'value' => 'Data Science'],
            ['id' => 3, 'name' => 'Engineering', 'value' => 'Engineering'],
            ['id' => 4, 'name' => 'App Development', 'value' => 'App Development'],
            ['id' => 5, 'name' => 'Web Development', 'value' => 'Web Development'],
            ['id' => 6, 'name' => 'Exam', 'value' => 'Exam'],
            ['id' => 7, 'name' => 'Public Health', 'value' => 'Public Health'],
            ['id' => 8, 'name' => 'Presentation (PPT)', 'value' => 'Presentation'],
            ['id' => 9, 'name' => 'Portfolio', 'value' => 'Portfolio'],
            ['id' => 10, 'name' => 'Research Report', 'value' => 'Research Report'],
            ['id' => 11, 'name' => 'Business Management', 'value' => 'Business Management'],
            ['id' => 12, 'name' => 'Project Management', 'value' => 'Project Management'],
            ['id' => 13, 'name' => 'Essay', 'value' => 'Essay'],
            ['id' => 14, 'name' => 'HRM', 'value' => 'HRM'],
            ['id' => 15, 'name' => 'Economic', 'value' => 'Economic'],
            ['id' => 16, 'name' => 'Other', 'value' => 'Other'],
        ];

        return response()->json([
            'success' => true,
            'data' => $subjects
        ]);
    }

    public function urgencies()
    {
        $urgencies = [];

        $multipliers = [
            1 => 2.0,
            2 => 1.5,
            3 => 1.4,
            4 => 1.3,
            5 => 1.2,
            6 => 1.15,
            7 => 1.1,
            8 => 1.09,
            9 => 1.07,
            10 => 1.05,
            11 => 1.04,
            12 => 1.03,
            13 => 1.02,
            14 => 1.01,
            15 => 1.0,
        ];

        for ($i = 1; $i <= 15; $i++) {
            $urgencies[] = [
                'id' => $i,
                'name' => $i . ' Day' . ($i > 1 ? 's' : ''),
                'value' => (string) $i,
                'multiplier' => $multipliers[$i]
            ];
        }

        $urgencies[] = [
            'id' => 16,
            'name' => '16-20 Days',
            'value' => '16 to 20',
            'multiplier' => 0.95
        ];

        $urgencies[] = [
            'id' => 17,
            'name' => '21+ Days',
            'value' => '21+',
            'multiplier' => 0.9
        ];

        return response()->json([
            'success' => true,
            'data' => $urgencies
        ]);
    }

    public function wordCount()
    {
        $wordCounts = [
            ['id' => 1, 'name' => '250 Words', 'value' => 250, 'multiplier' => 2.67],
            ['id' => 2, 'name' => '500 Words', 'value' => 500, 'multiplier' => 2.22],
            ['id' => 3, 'name' => '750 Words', 'value' => 750, 'multiplier' => 2.22],
            ['id' => 4, 'name' => '1000 Words', 'value' => 1000, 'multiplier' => 1.94],
            ['id' => 5, 'name' => '1250 Words', 'value' => 1250, 'multiplier' => 1.94],
            ['id' => 6, 'name' => '1500 Words', 'value' => 1500, 'multiplier' => 1.94],
            ['id' => 7, 'name' => '1750 Words', 'value' => 1750, 'multiplier' => 1.94],
            ['id' => 8, 'name' => '2000 Words', 'value' => 2000, 'multiplier' => 1.67],
            ['id' => 9, 'name' => '2500 Words', 'value' => 2500, 'multiplier' => 1.67],
            ['id' => 10, 'name' => '3000 Words', 'value' => 3000, 'multiplier' => 1.30],
            ['id' => 11, 'name' => '4000 Words', 'value' => 4000, 'multiplier' => 1.13],
            ['id' => 12, 'name' => '5000 Words', 'value' => 5000, 'multiplier' => 1.17],
        ];

        return response()->json([
            'success' => true,
            'base_price_per_word' => 0.03,
            'discount_percentage' => 40,
            'data' => $wordCounts
        ]);
    }
}
