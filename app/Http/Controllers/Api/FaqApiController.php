<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FaqUrl;
use Illuminate\Http\Request;

class FaqApiController extends Controller
{
    /**
     * Get list of FAQ URLs/categories with their questions.
     */
    public function index(Request $request)
    {
        try {
            $query = FaqUrl::query();

            // Optional search filtering by name/title
            if ($request->has('search')) {
                $search = $request->query('search');
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('title', 'like', '%' . $search . '%');
            }

            // Get all FAQ categories with their associated FAQs
            $faqUrls = $query->with('faqs')->latest()->get();

            return response()->json([
                'success' => true,
                'data' => $faqUrls
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve FAQs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get FAQs of a specific URL/category by slug.
     */
    public function show($slug)
    {
        try {
            $slug = urldecode($slug);

            $faqUrl = FaqUrl::with('faqs')->where('slug', $slug)->first();

            if (!$faqUrl) {
                return response()->json([
                    'success' => false,
                    'message' => 'FAQ category not found'
                ], 404);
            }

            // Generate schema
            $schema = $this->generateFaqSchema($faqUrl->faqs->toArray());

            return response()->json([
                'success' => true,
                'data' => [
                    'faq_category' => $faqUrl,
                    'faqs' => $faqUrl->faqs,
                    'schema' => $schema
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper to generate FAQ Schema structure.
     */
    private function generateFaqSchema(array $faqEntries)
    {
        $mainEntity = array_map(function ($entry) {
            return [
                "@type" => "Question",
                "name" => $entry['question'] ?? '',
                "acceptedAnswer" => [
                    "@type" => "Answer",
                    "text" => $entry['answer'] ?? ''
                ]
            ];
        }, $faqEntries);

        return [
            "@context" => "https://schema.org",
            "@type" => "FAQPage",
            "mainEntity" => $mainEntity
        ];
    }
}
