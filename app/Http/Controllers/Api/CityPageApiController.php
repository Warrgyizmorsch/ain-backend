<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServicePage;
use App\Models\Experts;
use App\Models\Review;
use Illuminate\Http\Request;

class CityPageApiController extends Controller
{
    /**
     * Allowed static UK cities list
     */
    protected array $ukCities = [
        'edinburgh',
        'glasgow',
        'leeds',
        'sheffield',
        'liverpool',
        'nottingham',
        'bristol',
        'coventry',
        'cardiff',
        'belfast',
        'cambridge',
        'oxford',
        'perth',
        'newcastle',
    ];

    /**
     * Get list of all published dynamic city pages.
     */
    public function index(Request $request)
    {
        try {
            // 1. Fetch DB-based city pages from ServicePage model
            $query = ServicePage::with('subject')
                ->where('is_published', true)
                ->where(function ($q) {
                    $q->whereHas('subject', function ($sq) {
                        $sq->whereIn('slug', ['city', 'cities'])
                          ->orWhere('name', 'like', '%city%')
                          ->orWhere('name', 'like', '%cities%');
                    })
                    ->orWhere('slug', 'like', 'city/%')
                    ->orWhere('slug', 'like', 'cities/%')
                    ->orWhere('slug', 'like', '%/city/%')
                    ->orWhere('slug', 'like', '%/cities/%');
                });

            if ($request->has('prefix')) {
                $prefix = $request->query('prefix');
                $query->where(function ($q) use ($prefix) {
                    $q->where('slug', 'like', '%' . $prefix . '%')
                      ->orWhereHas('subject', function ($sq) use ($prefix) {
                          $sq->where('slug', $prefix)
                            ->orWhere('name', 'like', '%' . $prefix . '%');
                      });
                });
            }

            $pages = $query->get();
            $formattedData = $this->formatPages($pages);

            // 2. Static city list
            $staticCityList = [];
            foreach ($this->ukCities as $cityKey) {
                $title = ucwords($cityKey);
                $slug = 'uk/assignment-help-' . $cityKey;
                $staticCityList[] = [
                    'id' => $cityKey,
                    'title' => 'Assignment Help ' . $title,
                    'slug' => $slug,
                    'city' => $title,
                    'hasSubmenu' => false,
                    'children' => [],
                ];
            }

            return response()->json([
                'status' => 'success',
                'data' => $formattedData,
                'static_cities' => $staticCityList
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve city pages: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get list of published dynamic city pages by prefix.
     */
    public function getByPrefix($prefix)
    {
        $prefix = urldecode($prefix);

        $pages = ServicePage::with('subject')
            ->where('is_published', true)
            ->where(function ($q) use ($prefix) {
                $q->where('slug', 'like', '%' . $prefix . '%')
                  ->orWhereHas('subject', function ($sq) use ($prefix) {
                      $sq->where('slug', $prefix)
                        ->orWhere('name', $prefix);
                  });
            })
            ->latest()
            ->get(['id', 'subject_id', 'slug', 'meta_title', 'hero_heading']);

        return response()->json([
            'success' => true,
            'data' => $pages
        ]);
    }

    /**
     * Get details of a specific dynamic city page by slug or city name.
     */
    public function show($slug)
    {
        try {
            $slug = urldecode($slug);
            $cleanSlug = trim($slug, '/');

            // 1. First search in ServicePage DB model
            $page = ServicePage::with('subject')
                ->where('is_published', true)
                ->where(function ($query) use ($cleanSlug) {
                    $query->where('slug', $cleanSlug)
                          ->orWhere('slug', 'like', '%/' . $cleanSlug)
                          ->orWhere('slug', 'city/' . $cleanSlug);
                })
                ->first();

            if ($page) {
                $experts = $page->selectedExperts()->map(function ($expert) {
                    if (!empty($expert->image) && !str_starts_with($expert->image, 'http')) {
                        $expert->image = request()->root() . '/' . ltrim($expert->image, '/');
                    }
                    return $expert;
                });

                $reviews = $page->selectedReviews();

                return response()->json([
                    'success' => true,
                    'data' => [
                        'page' => $page,
                        'experts' => $experts,
                        'reviews' => $reviews
                    ]
                ]);
            }

            // 2. Fallback: Search in UK Assignment City pages (dataload config)
            $cityKey = strtolower(str_replace(['uk/assignment-help-', 'assignment-help-', 'city/'], '', $cleanSlug));

            if (in_array($cityKey, $this->ukCities)) {
                $urlKey = "/uk/assignment-help-{$cityKey}";
                $dataload = config('dataload');

                if (isset($dataload[$urlKey])) {
                    $meta = $dataload[$urlKey]['meta'] ?? [];
                    $faqs = $dataload[$urlKey]['faqs'] ?? [];

                    $cityPageData = [
                        'id' => $cityKey,
                        'slug' => ltrim($urlKey, '/'),
                        'meta_title' => $meta['title'] ?? ('Assignment Help ' . ucwords($cityKey)),
                        'meta_description' => $meta['description'] ?? '',
                        'hero_heading' => $meta['h1'] ?? ('Assignment Help in ' . ucwords($cityKey)),
                        'faqs' => $faqs,
                        'is_published' => true,
                    ];

                    $experts = Experts::latest()->take(6)->get()->map(function ($expert) {
                        if (!empty($expert->image) && !str_starts_with($expert->image, 'http')) {
                            $expert->image = request()->root() . '/' . ltrim($expert->image, '/');
                        }
                        return $expert;
                    });

                    $reviews = Review::latest()->take(6)->get();

                    return response()->json([
                        'success' => true,
                        'data' => [
                            'page' => $cityPageData,
                            'experts' => $experts,
                            'reviews' => $reviews
                        ]
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'City page not found'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper to format pages into parent-child structure.
     */
    private function formatPages($pages)
    {
        $parents = [];
        $childrenByParent = [];

        foreach ($pages as $page) {
            $slug = trim($page->slug, '/');
            $segments = explode('/', $slug);

            if (count($segments) === 2) {
                $parentId = $segments[1];
                $parents[$parentId] = [
                    'id' => $page->id,
                    'title' => $page->hero_heading ?: ucwords(str_replace('-', ' ', $parentId)),
                    'slug' => $page->slug,
                    'hasSubmenu' => false,
                    'children' => [],
                    'order' => $page->id,
                ];
            } elseif (count($segments) >= 3) {
                $parentId = $segments[1];

                $childrenByParent[$parentId][] = [
                    'id' => $page->id,
                    'title' => $page->hero_heading ?: $page->meta_title,
                    'slug' => $page->slug,
                    'order' => $page->id,
                ];
            } else {
                $parents[$page->id] = [
                    'id' => $page->id,
                    'title' => $page->hero_heading ?: $page->meta_title,
                    'slug' => $page->slug,
                    'hasSubmenu' => false,
                    'children' => [],
                    'order' => $page->id,
                ];
            }
        }

        foreach ($childrenByParent as $parentId => $children) {
            if (!isset($parents[$parentId])) {
                $parents[$parentId] = [
                    'id' => $parentId,
                    'title' => ucwords(str_replace('-', ' ', $parentId)),
                    'slug' => 'city/' . $parentId,
                    'hasSubmenu' => true,
                    'children' => [],
                    'order' => 0,
                ];
            }
        }

        foreach ($parents as $parentId => &$parent) {
            if (isset($childrenByParent[$parentId])) {
                usort($childrenByParent[$parentId], function ($a, $b) {
                    return $a['order'] <=> $b['order'];
                });

                $parent['children'] = array_map(function ($child) {
                    unset($child['order']);
                    return $child;
                }, $childrenByParent[$parentId]);

                $parent['hasSubmenu'] = true;
            }
        }
        unset($parent);

        return array_values($parents);
    }
}
