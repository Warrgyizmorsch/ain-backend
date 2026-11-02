<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrimeServicePage;
use App\Models\PrimeSubject;
use Illuminate\Http\Request;

class PrimeServicePageApiController extends Controller
{
    /**
     * Get list of all published prime dynamic service pages.
     */
    public function index(Request $request)
    {
        $query = PrimeServicePage::with('primeSubject')
            ->where('is_published', true);

        // Filter by prefix query parameter if provided
        if ($request->has('prefix')) {
            $prefix = $request->query('prefix');
            $query->where(function ($q) use ($prefix) {
                $q->whereHas('primeSubject', function ($sq) use ($prefix) {
                    $sq->where('slug', $prefix)
                       ->orWhere('name', 'like', '%' . $prefix . '%');
                })->orWhere('slug', 'like', $prefix . '/%')
                  ->orWhere('slug', $prefix);
            });

            $pages = $query->get();
            $formatted = $this->formatPages($pages);

            return response()->json([
                'status' => 'success',
                'data'   => $formatted,
            ]);
        }

        $pages = $query->get();

        $servicePages = [];
        $cityPages = [];

        foreach ($pages as $page) {
            $slug = trim($page->slug, '/');
            $segments = explode('/', $slug);

            $firstSegment = strtolower($segments[0] ?? '');
            $subjectSlug  = strtolower($page->primeSubject->slug ?? '');
            $subjectName  = strtolower($page->primeSubject->name ?? '');

            if (
                in_array($firstSegment, ['city', 'cities']) ||
                in_array($subjectSlug, ['city', 'cities']) ||
                str_contains($firstSegment, 'city') ||
                str_contains($firstSegment, 'cities') ||
                str_contains($subjectSlug, 'city') ||
                str_contains($subjectSlug, 'cities') ||
                str_contains($subjectName, 'city') ||
                str_contains($subjectName, 'cities')
            ) {
                $cityPages[] = $page;
            } else {
                $servicePages[] = $page;
            }
        }

        return response()->json([
            'status' => 'success',
            'data'   => $this->formatPages($servicePages),
            'city'   => $this->formatPages($cityPages),
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
                    'id'         => $page->id,
                    'title'      => $page->hero_heading,
                    'slug'       => $page->slug,
                    'hasSubmenu' => false,
                    'children'   => [],
                    'order'      => $page->id,
                ];
            } elseif (count($segments) === 3) {
                $parentId = $segments[1];

                $childrenByParent[$parentId][] = [
                    'id'    => $page->id,
                    'title' => $page->hero_heading,
                    'slug'  => $page->slug,
                    'order' => $page->id,
                ];
            }
        }

        foreach ($childrenByParent as $parentId => $children) {
            if (!isset($parents[$parentId])) {
                $parents[$parentId] = [
                    'id'         => $parentId,
                    'title'      => ucwords(str_replace('-', ' ', $parentId)),
                    'slug'       => $parentId,
                    'hasSubmenu' => true,
                    'children'   => [],
                    'order'      => 0,
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
            } else {
                $parent['hasSubmenu'] = false;
                $parent['children'] = [];
            }
        }
        unset($parent);

        usort($parents, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });

        return array_map(function ($parent) {
            unset($parent['order']);
            return $parent;
        }, $parents);
    }

    /**
     * Get list of published prime dynamic service pages by prefix slug or name.
     */
    public function getByPrefix($prefix)
    {
        $prefix = urldecode($prefix);

        $pages = PrimeServicePage::with('primeSubject')
            ->where('is_published', true)
            ->where(function ($query) use ($prefix) {
                $query->whereHas('primeSubject', function ($q) use ($prefix) {
                    $q->where('slug', $prefix)
                      ->orWhere('name', $prefix);
                })->orWhere('slug', 'like', $prefix . '/%')
                  ->orWhere('slug', $prefix);
            })
            ->latest('id')
            ->get(['id', 'prime_subject_id', 'slug', 'meta_title', 'hero_heading']);

        return response()->json([
            'success' => true,
            'data'    => $pages,
        ]);
    }

    /**
     * Get details of a specific prime dynamic service page by slug.
     */
    public function show($slug)
    {
        $slug = urldecode($slug);

        $page = PrimeServicePage::with('primeSubject')
            ->where(function ($query) use ($slug) {
                $query->where('slug', $slug)
                      ->orWhere('slug', 'like', '%/' . $slug);
            })
            ->where('is_published', true)
            ->first();

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Prime service page not found',
            ], 404);
        }

        $experts = $page->selectedExperts();
        $reviews = $page->selectedReviews();

        return response()->json([
            'success' => true,
            'data'    => [
                'page'    => $page,
                'experts' => $experts,
                'reviews' => $reviews,
            ],
        ]);
    }

    /**
     * Get active prefixes for Prime Assignment.
     */
    public function subjects()
    {
        $subjects = PrimeSubject::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return response()->json([
            'success' => true,
            'data'    => $subjects,
        ]);
    }
}

