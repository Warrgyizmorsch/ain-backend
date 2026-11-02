<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrimeServicePage;
use App\Models\PrimeSubject;
use Illuminate\Http\Request;

class PrimeServicePageApiController extends Controller
{
    public function __construct()
    {
        \App\Services\PrimeAssignmentSetupService::ensureSetup();
    }

    /**
     * Get list of Prime dynamic service pages (Strictly isolated).
     */
    public function index(Request $request)
    {
        $query = PrimeServicePage::with('primeSubject');

        // Optional prefix filter
        if ($request->has('prefix')) {
            $prefix = $request->query('prefix');
            $query->where(function ($q) use ($prefix) {
                $q->whereHas('primeSubject', function ($sq) use ($prefix) {
                    $sq->where('slug', $prefix)
                       ->orWhere('name', 'like', '%' . $prefix . '%');
                })->orWhere('slug', 'like', $prefix . '/%')
                  ->orWhere('slug', 'like', '%/' . $prefix)
                  ->orWhere('slug', $prefix);
            });

            $pages = $query->get();
            $formatted = $this->formatPages($pages);

            return response()->json([
                'status'    => 'success',
                'success'   => true,
                'data'      => $formatted,
                'all_pages' => $pages,
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
            'status'    => 'success',
            'success'   => true,
            'data'      => $this->formatPages($servicePages),
            'city'      => $this->formatPages($cityPages),
            'all_pages' => $pages,
            'pages'     => $pages,
        ]);
    }

    /**
     * Helper to format pages into parent-child structure.
     * Supports 1-segment, 2-segment, and 3-segment slugs.
     */
    private function formatPages($pages)
    {
        $parents = [];
        $childrenByParent = [];

        foreach ($pages as $page) {
            $slug = trim($page->slug, '/');
            $segments = explode('/', $slug);

            if (count($segments) === 1) {
                // Top-level custom slug e.g. /testing or /assignment-help
                $parentId = $segments[0];
                $parents[$parentId] = [
                    'id'         => $page->id,
                    'title'      => $page->hero_heading ?: ucwords(str_replace('-', ' ', $parentId)),
                    'slug'       => $page->slug,
                    'hasSubmenu' => false,
                    'children'   => [],
                    'order'      => $page->id,
                ];
            } elseif (count($segments) === 2) {
                // Prefix + slug e.g. /service/accounting-help
                $parentId = $segments[1];
                $parents[$parentId] = [
                    'id'         => $page->id,
                    'title'      => $page->hero_heading ?: ucwords(str_replace('-', ' ', $parentId)),
                    'slug'       => $page->slug,
                    'hasSubmenu' => false,
                    'children'   => [],
                    'order'      => $page->id,
                ];
            } elseif (count($segments) >= 3) {
                // Sub-page e.g. /service/dissertation/uk
                $parentId = $segments[1];

                $childrenByParent[$parentId][] = [
                    'id'    => $page->id,
                    'title' => $page->hero_heading ?: ucwords(str_replace('-', ' ', $segments[2])),
                    'slug'  => $page->slug,
                    'order' => $page->id,
                ];
            }
        }

        // Auto-create parents for children that do not have an explicit parent page in DB
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

        return array_values(array_map(function ($parent) {
            unset($parent['order']);
            return $parent;
        }, $parents));
    }

    /**
     * Get list of prime dynamic service pages by prefix slug or name.
     */
    public function getByPrefix($prefix)
    {
        $prefix = urldecode($prefix);

        $pages = PrimeServicePage::with('primeSubject')
            ->where(function ($query) use ($prefix) {
                $query->whereHas('primeSubject', function ($q) use ($prefix) {
                    $q->where('slug', $prefix)
                      ->orWhere('name', $prefix);
                })->orWhere('slug', 'like', $prefix . '/%')
                  ->orWhere('slug', 'like', '%/' . $prefix)
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
     * Get details of a specific prime dynamic service page by slug, title, or prefix.
     */
    public function show($slug)
    {
        $slug = urldecode($slug);
        $cleanSlug = trim($slug, '/');

        // 1. Check in PrimeServicePage by slug
        $page = PrimeServicePage::with('primeSubject')
            ->where(function ($query) use ($slug, $cleanSlug) {
                $query->where('slug', $cleanSlug)
                      ->orWhere('slug', '/' . $cleanSlug)
                      ->orWhere('slug', $slug)
                      ->orWhere('slug', 'like', '%/' . $cleanSlug)
                      ->orWhere('slug', 'like', $cleanSlug . '/%')
                      ->orWhere('slug', 'like', '%' . $cleanSlug . '%');
                if (is_numeric($cleanSlug)) {
                    $query->orWhere('id', (int) $cleanSlug);
                }
            })
            ->first();

        // 2. If no slug match, try matching by page title / hero_heading
        if (!$page) {
            $page = PrimeServicePage::with('primeSubject')
                ->where(function ($query) use ($cleanSlug) {
                    $query->where('hero_heading', 'like', '%' . $cleanSlug . '%')
                          ->orWhere('meta_title', 'like', '%' . $cleanSlug . '%');
                })
                ->first();
        }

        // 3. If still no page, check if $slug is a Prefix (PrimeSubject)
        if (!$page) {
            $subject = PrimeSubject::where('slug', $cleanSlug)
                ->orWhere('name', 'like', '%' . $cleanSlug . '%')
                ->first();

            if ($subject) {
                // Find first page with this prefix
                $page = PrimeServicePage::with('primeSubject')
                    ->where('prime_subject_id', $subject->id)
                    ->orWhere('slug', 'like', $subject->slug . '/%')
                    ->first();

                if ($page) {
                    return response()->json([
                        'success' => true,
                        'matched' => 'prefix_page',
                        'prefix'  => $subject->slug,
                        'data'    => [
                            'page'    => $page,
                            'experts' => $page->selectedExperts(),
                            'reviews' => $page->selectedReviews(),
                        ],
                    ]);
                }

                $prefixPages = PrimeServicePage::with('primeSubject')
                    ->where('prime_subject_id', $subject->id)
                    ->orWhere('slug', 'like', $subject->slug . '/%')
                    ->get();

                return response()->json([
                    'success' => true,
                    'matched' => 'prefix',
                    'prefix'  => $subject->slug,
                    'data'    => $prefixPages,
                ]);
            }
        }

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => "Prime service page not found for slug '{$slug}'",
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
     * Get active prefixes for Prime Assignment only.
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
