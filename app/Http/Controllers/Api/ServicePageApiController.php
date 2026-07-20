<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServicePage;
use Illuminate\Http\Request;

class ServicePageApiController extends Controller
{
    /**
     * Get list of all published dynamic service pages.
     */
    public function index(Request $request)
    {
        $query = ServicePage::with('subject')
            ->whereHas('subject')
            ->where('is_published', true);

        // Filter by prefix query parameter if provided
        if ($request->has('prefix')) {
            $prefix = $request->query('prefix');
            $query->whereHas('subject', function ($q) use ($prefix) {
                $q->where('slug', $prefix)
                  ->orWhere('name', 'like', '%' . $prefix . '%');
            });

            $pages = $query->get();
            $formatted = $this->formatPages($pages);

            $response = [
                'status' => 'success',
                'data' => $formatted
            ];

            if ($prefix === 'city') {
                $response['city'] = $formatted;
            }

            return response()->json($response);
        }

        $pages = $query->get();

        $servicePages = [];
        $cityPages = [];

        foreach ($pages as $page) {
            $slug = trim($page->slug, '/');
            $segments = explode('/', $slug);

            if (($segments[0] ?? '') === 'city' || ($page->subject->slug ?? '') === 'city') {
                $cityPages[] = $page;
            } else {
                $servicePages[] = $page;
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $this->formatPages($servicePages),
            'city' => $this->formatPages($cityPages)
        ]);
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
                    'title' => $page->hero_heading,
                    'slug' => $page->slug,
                    'hasSubmenu' => false,
                    'children' => [],
                    'order' => $page->id,
                ];
            } elseif (count($segments) === 3) {
                $parentId = $segments[1];
                $childId = $segments[2];

                $childrenByParent[$parentId][] = [
                    'id' => $page->id,
                    'title' => $page->hero_heading,
                    'slug' => $page->slug,
                    'order' => $page->id,
                ];
            }
        }

        // Auto-create parents for children that do not have an explicit parent page in DB
        foreach ($childrenByParent as $parentId => $children) {
            if (!isset($parents[$parentId])) {
                // Determine prefix from the first child slug if available
                $prefix = 'service';
                if (!empty($children)) {
                    $childSlug = trim($children[0]['slug'], '/');
                    $parts = explode('/', $childSlug);
                    if (count($parts) > 0) {
                        $prefix = $parts[0];
                    }
                }

                $parents[$parentId] = [
                    'id' => $parentId,
                    'title' => ucwords(str_replace('-', ' ', $parentId)),
                    'slug' => $parentId,
                    'hasSubmenu' => true,
                    'children' => [],
                    'order' => 0,
                ];
            }
        }

        foreach ($parents as $parentId => &$parent) {
            if (isset($childrenByParent[$parentId])) {
                usort($childrenByParent[$parentId], function($a, $b) {
                    return $a['order'] <=> $b['order'];
                });

                $parent['children'] = array_map(function($child) {
                    unset($child['order']);
                    return $child;
                }, $childrenByParent[$parentId]);

                $parent['hasSubmenu'] = true;
            } else {
                $parent['hasSubmenu'] = false;
                $parent['children'] = [];
            }
        }
        unset($parent);

        usort($parents, function($a, $b) {
            return $a['order'] <=> $b['order'];
        });

        $formattedData = array_map(function($parent) {
            unset($parent['order']);
            return $parent;
        }, $parents);

        return $formattedData;
    }

    /**
     * Get list of published dynamic service pages by prefix slug or name.
     */
    public function getByPrefix($prefix)
    {
        $prefix = urldecode($prefix);

        $pages = ServicePage::with('subject')
            ->where('is_published', true)
            ->whereHas('subject', function ($q) use ($prefix) {
                $q->where('slug', $prefix)
                  ->orWhere('name', $prefix);
            })
            ->latest()
            ->get(['id', 'subject_id', 'slug', 'meta_title', 'hero_heading']);

        return response()->json([
            'success' => true,
            'data' => $pages
        ]);
    }

    /**
     * Get details of a specific dynamic service page by slug.
     */
    public function show($slug)
    {
        $slug = urldecode($slug);

        $page = ServicePage::with('subject')
            ->where(function ($query) use ($slug) {
                $query->where('slug', $slug)
                      ->orWhere('slug', 'like', '%/' . $slug);
            })
            ->where('is_published', true)
            ->first();

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Service page not found'
            ], 404);
        }

        // Fetch related experts and reviews using model methods
        $experts = $page->selectedExperts();
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
}
