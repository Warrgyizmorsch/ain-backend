<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrimeSample;
use App\Models\PrimeServicePage;
use App\Models\PrimeSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PrimeServicePageApiController extends Controller
{
    public function __construct()
    {
        \App\Services\PrimeAssignmentSetupService::ensureSetup();
    }

    /**
     * Get list of Prime dynamic service, subject, and city pages (Strictly isolated).
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
        $subjectPages = [];
        $cityPages = [];

        foreach ($pages as $page) {
            $type = $this->resolveStoredType($page);

            if ($type === 'subject') {
                $subjectPages[] = $page;
            } elseif ($type === 'city') {
                $cityPages[] = $page;
            } else {
                $servicePages[] = $page;
            }
        }

        $typeFilter = strtolower($request->query('type') ?? '');
        if ($typeFilter === 'subject') {
            $mainData = $this->formatPages($subjectPages);
        } elseif ($typeFilter === 'city') {
            $mainData = $this->formatPages($cityPages);
        } else {
            $mainData = $this->formatPages($servicePages);
        }

        return response()->json([
            'status'    => 'success',
            'success'   => true,
            'data'      => $mainData,
            'service'   => $this->formatPages($servicePages),
            'services'  => $this->formatPages($servicePages),
            'subject'   => $this->formatPages($subjectPages),
            'subjects'  => $this->formatPages($subjectPages),
            'city'      => $this->formatPages($cityPages),
            'cities'    => $this->formatPages($cityPages),
            'all_pages' => $pages,
            'pages'     => $pages,
        ]);
    }

    /**
     * Dedicated listing for Prime Subject Pages.
     */
    public function subjectIndex(Request $request)
    {
        $request->merge(['type' => 'subject']);
        return $this->index($request);
    }

    /**
     * Dedicated listing for Prime City Pages.
     */
    public function cityIndex(Request $request)
    {
        $request->merge(['type' => 'city']);
        return $this->index($request);
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
     * Resolve a page's type from its DB column, falling back to slug / prefix inference.
     * Does NOT look at the request path, so it is safe to use for filtering.
     */
    private function resolveStoredType(PrimeServicePage $page): string
    {
        $type = strtolower(trim((string) ($page->type ?? '')));
        if (in_array($type, ['service', 'subject', 'city'])) {
            return $type;
        }

        $slug = trim((string) $page->slug, '/');
        $firstSegment = strtolower(explode('/', $slug)[0] ?? '');
        $subjectSlug  = strtolower($page->primeSubject->slug ?? '');
        $subjectName  = strtolower($page->primeSubject->name ?? '');

        if (
            str_contains($firstSegment, 'city') ||
            str_contains($subjectSlug, 'city') ||
            str_contains($subjectName, 'city')
        ) {
            return 'city';
        }

        if (
            str_contains($firstSegment, 'subject') ||
            str_contains($subjectSlug, 'subject') ||
            str_contains($subjectName, 'subject')
        ) {
            return 'subject';
        }

        return 'service';
    }

    /** Detail endpoint restricted to service pages. */
    public function showService($slug)
    {
        return $this->show($slug, 'service');
    }

    /** Detail endpoint restricted to subject pages. */
    public function showSubject($slug)
    {
        return $this->show($slug, 'subject');
    }

    /** Detail endpoint restricted to city pages. */
    public function showCity($slug)
    {
        return $this->show($slug, 'city');
    }

    /**
     * Get details of a specific prime dynamic page by slug, title, or prefix.
     * When $type is given, only pages of that type are returned (404 otherwise).
     */
    public function show($slug, ?string $type = null)
    {
        $slug = urldecode($slug);
        $cleanSlug = trim($slug, '/');

        $matchesType = fn (PrimeServicePage $p) => $type === null || $this->resolveStoredType($p) === $type;

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
            ->get()
            ->first($matchesType);

        // 2. If no slug match, try matching by page title / hero_heading
        if (!$page) {
            $page = PrimeServicePage::with('primeSubject')
                ->where(function ($query) use ($cleanSlug) {
                    $query->where('hero_heading', 'like', '%' . $cleanSlug . '%')
                          ->orWhere('meta_title', 'like', '%' . $cleanSlug . '%');
                })
                ->get()
                ->first($matchesType);
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
                    ->get()
                    ->first($matchesType);
            }
        }

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => $type
                    ? "Prime {$type} page not found for slug '{$slug}'"
                    : "Prime page not found for slug '{$slug}'",
            ], 404);
        }

        return response()->json([
            'success'   => true,
            'data'      => $this->transformPage($page),
            'timestamp' => now()->toIso8601ZuluString(),
        ]);
    }

    /**
     * Transform a PrimeServicePage into the full rich schema requested by frontend.
     */
    public function transformPage(PrimeServicePage $page): array
    {
        $name = $page->primeSubject?->name ?: $this->extractSubjectName($page);

        // Resolve pageType:
        // Priority 1: Explicit DB type if set ('service', 'subject', 'city')
        // Priority 2: Inferred from request path
        // Priority 3: Inferred from slug
        // Priority 4: Default to 'service'
        $dbType = strtolower(trim((string) ($page->type ?? '')));
        if (in_array($dbType, ['service', 'subject', 'city'])) {
            $pageType = $dbType;
        } elseif (request()->is('*city*')) {
            $pageType = 'city';
        } elseif (request()->is('*subject*')) {
            $pageType = 'subject';
        } elseif (request()->is('*service*')) {
            $pageType = 'service';
        } else {
            $slugLower = strtolower(trim($page->slug ?? '', '/'));
            if (str_starts_with($slugLower, 'city/') || str_contains($slugLower, '/city/')) {
                $pageType = 'city';
            } elseif (str_starts_with($slugLower, 'subject/') || str_contains($slugLower, '/subject/')) {
                $pageType = 'subject';
            } else {
                $pageType = 'service';
            }
        }

        // 1. Topics: custom in DB > extracted from HTML table > subject-aware default
        $topics = $page->topics;
        if (empty($topics) || !is_array($topics)) {
            $topics = $this->getDefaultTopics($page, $name);
        }

        // 2. Why Choose Us Heading
        $whyTitle = $page->why_heading ?: 'Why Students Choose';
        $whyHighlight = $page->why_subheading ?: "Our {$name} Help";
        if (empty($page->why_heading) || $whyTitle === $whyHighlight || str_contains(strtolower($whyTitle), 'assignment help')) {
            $whyTitle = 'Why Students Choose';
            $whyHighlight = "Our {$name} Help";
        }
        $whyChooseUsHeading = [
            'title'         => $whyTitle,
            'highlightWord' => $whyHighlight,
        ];

        // 3. Why Choose Us Items
        $whyChooseUs = [];
        if (!empty($page->why_items) && is_array($page->why_items)) {
            foreach ($page->why_items as $item) {
                if (isset($item['heading']) || isset($item['t'])) {
                    $whyChooseUs[] = [
                        't' => $item['heading'] ?? $item['t'] ?? '',
                        'd' => $item['content'] ?? $item['d'] ?? '',
                    ];
                }
            }
        }
        if (empty($whyChooseUs)) {
            $whyChooseUs = [
                [
                    't' => 'Step-by-step reasoning',
                    'd' => "Every formula, rule, statutory provision and case authority is clearly written out so you can understand the exact method.",
                ],
                [
                    't' => 'Qualified subject specialists',
                    'd' => "All briefs are solved by postgraduate and doctoral {$name} specialists with proven UK university experience.",
                ],
                [
                    't' => 'Double error-checked',
                    'd' => 'Every derivation, equation, argument and citation is independently reviewed by a second specialist.',
                ],
                [
                    't' => 'Accredited format & citation',
                    'd' => 'Delivered in professional format fully compliant with your university referencing style (OSCOLA, Harvard, APA, or LaTeX).',
                ],
                [
                    't' => 'Student-friendly pricing',
                    'd' => 'Clear quotes from £4.00 per page, with no hidden extras.',
                ],
            ];
        }

        // 4. Experts
        $expertsList = [];
        $selectedExperts = $page->selectedExperts();
        if ($selectedExperts->isNotEmpty()) {
            foreach ($selectedExperts as $exp) {
                $expertsList[] = [
                    'name'   => $exp->name ?: 'Academic Specialist',
                    'degree' => $exp->subject ? "Specialist in {$exp->subject}" : "Postgraduate {$name} Specialist",
                    'desc'   => $exp->content ?: ($exp->meta_description ?: "Experienced {$name} specialist with 10+ years academic tutoring."),
                    'rating' => '4.98',
                    'orders' => ($exp->finish_order ?: 1200) . '+',
                    'image'  => $exp->image ? url($exp->image) : '/images/home/why-choose/expert-1.webp',
                ];
            }
        }
        if (empty($expertsList)) {
            $isLaw = str_contains(strtolower($name), 'law') || str_contains(strtolower($page->slug), 'law');
            if ($isLaw) {
                $expertsList = [
                    [
                        'name'   => 'Eleanor Vance, LL.M.',
                        'degree' => 'LL.M. in Corporate & Commercial Law (LSE)',
                        'desc'   => 'Specialist in Companies Act 2006, Director Duties, Shareholder Remedies & Corporate Insolvency with 10+ years tutoring.',
                        'rating' => '4.98',
                        'orders' => '1,420+',
                        'image'  => '/images/home/why-choose/expert-1.webp',
                    ],
                    [
                        'name'   => 'Marcus Sterling, BCL',
                        'degree' => 'BCL in Commercial Law (Oxford)',
                        'desc'   => 'Corporate governance, veil piercing, cross-border M&A and regulatory compliance specialist.',
                        'rating' => '4.95',
                        'orders' => '1,180+',
                        'image'  => '/images/home/why-choose/expert-2.webp',
                    ],
                    [
                        'name'   => 'Rachel Thornton, LL.B. (Hons)',
                        'degree' => 'Postgraduate Diploma in Legal Practice (BPP London)',
                        'desc'   => 'Commercial contracts, corporate criminal liability, OSCOLA citation & partnership law specialist.',
                        'rating' => '4.96',
                        'orders' => '980+',
                        'image'  => '/images/home/why-choose/expert-3.webp',
                    ],
                ];
            } else {
                $expertsList = [
                    [
                        'name'   => 'Dr. Alistair Finch',
                        'degree' => "Ph.D. in {$name} (Warwick)",
                        'desc'   => "Academic specialist in advanced {$name} coursework and research methodology with 12+ years tutoring.",
                        'rating' => '4.98',
                        'orders' => '1,420+',
                        'image'  => '/images/home/why-choose/expert-1.webp',
                    ],
                    [
                        'name'   => 'Dr. Rebecca Thornton',
                        'degree' => "Ph.D. in Applied {$name} (Imperial)",
                        'desc'   => "Modelling, empirical analysis, and dissertation specialist for UK postgraduate courses.",
                        'rating' => '4.95',
                        'orders' => '1,180+',
                        'image'  => '/images/home/why-choose/expert-2.webp',
                    ],
                    [
                        'name'   => 'David Sterling, M.Sc.',
                        'degree' => "M.Sc. in {$name} (Oxford)",
                        'desc'   => "Specialist in UK university curriculum standards, data models and coursework problem solutions.",
                        'rating' => '4.96',
                        'orders' => '980+',
                        'image'  => '/images/home/why-choose/expert-3.webp',
                    ],
                ];
            }
        }

        // 5. Reviews
        $reviewsList = [];
        $selectedReviews = $page->selectedReviews();
        if ($selectedReviews->isNotEmpty()) {
            foreach ($selectedReviews as $rev) {
                $reviewsList[] = [
                    'category' => $rev->services_type ?: $name,
                    'text'     => $rev->description ?: 'Clear step-by-step guidance provided. Highly recommended!',
                    'student'  => $rev->name ?: 'Student',
                    'course'   => $rev->location ?: 'UK University',
                    'grade'    => 'First Class',
                    'rating'   => (int) ($rev->customer_rating ?: 5),
                ];
            }
        }
        if (empty($reviewsList)) {
            $isLaw = str_contains(strtolower($name), 'law') || str_contains(strtolower($page->slug), 'law');
            $reviewsList = [
                [
                    'category' => $name,
                    'text'     => $isLaw
                        ? 'I was stuck on difficult problem questions involving director duties and wrongful trading. The model answer applied Companies Act sections and OSCOLA citations with great precision. Scored 84% on my coursework!'
                        : "I was stuck on difficult {$name} coursework problems. The solution had every single step and calculation written out clearly. Scored 84% on my coursework!",
                    'student'  => 'Oliver B.',
                    'course'   => $isLaw ? 'LL.B. Law, University of Manchester' : "B.Sc. {$name}, University of Manchester",
                    'grade'    => 'First Class',
                    'rating'   => 5,
                ],
                [
                    'category' => $name,
                    'text'     => $isLaw
                        ? 'The statutory analysis and case law evaluations were delivered 2 days ahead of my deadline. Perfectly cited cases and clear arguments throughout.'
                        : "Delivered 2 days ahead of my deadline. Perfectly commented work and clear explanations throughout.",
                    'student'  => 'Charlotte W.',
                    'course'   => $isLaw ? 'LL.M. Corporate Law, University of Leeds' : "M.Sc. {$name}, University of Leeds",
                    'grade'    => 'Distinction',
                    'rating'   => 5,
                ],
            ];
        }

        // 6. Samples
        $samplesList = [];
        try {
            if (Schema::hasTable('prime_samples')) {
                $samples = PrimeSample::with('category')->where('is_published', true)->latest('id')->limit(3)->get();
                foreach ($samples as $sample) {
                    $samplesList[] = [
                        't'    => $sample->title,
                        'type' => $sample->category?->name ?: $name,
                        'w'    => number_format($sample->words_count ?: 3000),
                        'd'    => number_format((int) (($sample->words_count ?: 3000) * 0.75)),
                    ];
                }
            }
        } catch (\Throwable $e) {}

        if (empty($samplesList)) {
            $isLaw = str_contains(strtolower($name), 'law') || str_contains(strtolower($page->slug), 'law');
            if ($isLaw) {
                $samplesList = [
                    [
                        't'    => 'Director Duties Under Section 172 and Creditor Interests: Sequana Case Analysis',
                        'type' => 'Corporate Law',
                        'w'    => '3,200',
                        'd'    => '2,450',
                    ],
                    [
                        't'    => 'Piercing the Corporate Veil Post-Prest v Petrodel: Evasion vs Concealment',
                        'type' => 'Company Law',
                        'w'    => '4,100',
                        'd'    => '3,120',
                    ],
                    [
                        't'    => 'Shareholder Remedies Under Section 994 Companies Act 2006: Unfair Prejudice in Quasi-Partnerships',
                        'type' => 'Corporate Governance',
                        'w'    => '2,800',
                        'd'    => '1,890',
                    ],
                ];
            } else {
                $samplesList = [
                    [
                        't'    => "Comprehensive Analysis of {$name} Principles & Worked Solutions",
                        'type' => $name,
                        'w'    => '3,200',
                        'd'    => '2,450',
                    ],
                    [
                        't'    => "Critical Literature Review & Empirical Methodology in {$name}",
                        'type' => $name,
                        'w'    => '4,100',
                        'd'    => '3,120',
                    ],
                    [
                        't'    => "Case Study & Theoretical Framework Applications in {$name}",
                        'type' => $name,
                        'w'    => '2,800',
                        'd'    => '1,890',
                    ],
                ];
            }
        }

        // 7. FAQs
        $faqsList = [];
        if (!empty($page->faqs) && is_array($page->faqs)) {
            foreach ($page->faqs as $faq) {
                $q = $faq['q'] ?? $faq['question'] ?? '';
                $a = $faq['a'] ?? $faq['answer'] ?? '';
                if ($q || $a) {
                    $faqsList[] = ['q' => $q, 'a' => $a];
                }
            }
        }
        if (empty($faqsList)) {
            $lowerSub = strtolower($name);
            $faqsList = [
                [
                    'q' => "Can I get online {$lowerSub} assignment help for urgent deadlines?",
                    'a' => "Yes, we provide urgent online {$lowerSub} assignment help for students who need quick academic support. Our experts work efficiently to deliver high-quality assignments within short deadlines without compromising on accuracy, structure, or academic standards.",
                ],
                [
                    'q' => "Do you provide plagiarism-free {$lowerSub} assignments?",
                    'a' => "Yes, every assignment is prepared completely from scratch based on your academic requirements. We ensure originality in every solution and carefully review all content to provide plagiarism-free work that meets university guidelines.",
                ],
                [
                    'q' => "Will I get step-by-step workings and explanations?",
                    'a' => "Yes. Every solution shows the full working, names the rule or method used at each step and checks the final answer, so you can follow and understand the complete approach.",
                ],
            ];
        }

        // 8. SEO
        $seoKeywords = [
            $page->hero_heading,
            "{$name} Assignment Help",
            "{$name} Assignment Help UK",
            "{$name} Coursework Help",
            "UK {$name} Specialists",
            "{$name} Case Law Help",
        ];
        $seo = [
            'title'       => $page->meta_title ?: $page->hero_heading,
            'description' => $page->meta_description ?: $page->hero_content,
            'keywords'    => array_values(array_unique(array_filter($seoKeywords))),
            'canonical'   => 'https://primeassignmenthelp.co.uk/' . trim($page->slug, '/') . '/',
        ];

        // 9. Content HTML
        $contentHtml = $page->long_content;
        if (empty($contentHtml)) {
            $contentHtml = '';
            if ($page->section_two_heading || $page->section_two_content) {
                if ($page->section_two_heading) {
                    $contentHtml .= '<h2>' . e($page->section_two_heading) . '</h2>';
                }
                if ($page->section_two_content) {
                    $contentHtml .= '<p>' . nl2br(e($page->section_two_content)) . '</p>';
                }
            }
            if ($page->section_three_heading || $page->section_three_content) {
                if ($page->section_three_heading) {
                    $contentHtml .= '<h2>' . e($page->section_three_heading) . '</h2>';
                }
                if ($page->section_three_content) {
                    $contentHtml .= '<p>' . nl2br(e($page->section_three_content)) . '</p>';
                }
            }
            if (empty($contentHtml)) {
                $contentHtml = '<h2>Expert ' . e($page->hero_heading) . ' for UK Students</h2>'
                    . '<p>' . nl2br(e($page->hero_content)) . '</p>';
            }
        }

        $data = [
            'slug'     => trim($page->slug, '/'),
            'pageType' => $pageType,
        ];

        if ($pageType === 'service') {
            $data['serviceName'] = $name;
        } elseif ($pageType === 'city') {
            $data['cityName'] = $name;
        } else {
            $data['subjectName'] = $name;
        }

        $data['title']              = $page->meta_title ?: $page->hero_heading;
        $data['h1']                 = $page->hero_heading;
        $data['h1Highlight']        = $page->hero_highlight ?: '';
        $data['sub']                = $page->hero_content;
        $data['brand']              = $page->brand ?: '#1E50E6';
        $data['brandSoft']          = $page->brand_soft ?: '#EBF0FD';
        $data['rating']             = '4.9';
        $data['ratingCount']        = '12,450+';
        $data['experienceYears']    = '10+';
        $data['discountPercentage'] = '45%';
        $data['startingPrice']      = '£4.00';
        $data['originalPrice']      = '£9.00';
        $data['stats']              = $page->stats ?: [
            'delivered' => '4,800+',
            'experts'   => '120+',
            'rating'    => '4.9/5',
            'onTime'    => '96%',
        ];
        $data['topics']             = $topics;
        $data['whyChooseUsHeading'] = $whyChooseUsHeading;
        $data['whyChooseUs']        = $whyChooseUs;
        $data['experts']            = $expertsList;
        $data['reviews']            = $reviewsList;
        $data['samples']            = $samplesList;
        $data['faqs']               = $faqsList;
        $data['seo']                = $seo;
        $data['contentHtml']        = $contentHtml;

        return $data;
    }

    private function extractSubjectName(PrimeServicePage $page): string
    {
        if ($page->primeSubject && $page->primeSubject->name) {
            return $page->primeSubject->name;
        }

        $heading = $page->hero_heading ?: '';
        $cleaned = preg_replace('/(\s+assignment|\s+help|\s+services|\s+uk).*$/i', '', $heading);
        $cleaned = trim($cleaned);

        if (!empty($cleaned) && strlen($cleaned) <= 30) {
            return $cleaned;
        }

        $slugPart = explode('/', trim($page->slug, '/'))[0] ?? '';
        $slugPart = preg_replace('/(-assignment|-help|-services|-uk).*$/i', '', $slugPart);
        return ucwords(str_replace('-', ' ', $slugPart)) ?: 'General';
    }

    private function getDefaultTopics(PrimeServicePage $page, string $subjectName): array
    {
        // 1. Try to extract topics from long_content table if available
        $extracted = $this->extractTopicsFromContent($page->long_content ?: '');
        if (!empty($extracted)) {
            return $extracted;
        }

        $isLaw = str_contains(strtolower($subjectName), 'law') || str_contains(strtolower($page->slug), 'law');
        if ($isLaw) {
            return [
                [
                    't' => 'Director Duties & Corporate Governance',
                    'f' => 'ss 171–177 + UK Corporate Governance Code',
                    'd' => 'Peskin v Anderson, Re City Equitable Fire Insurance Co, Item Software v Fassihi, Criterion Properties v Stratford UK Properties.',
                ],
                [
                    't' => 'Corporate Personality & Veil Piercing',
                    'f' => 's 16 + Insolvency Act 1986',
                    'd' => 'Salomon v Salomon & Co Ltd, Prest v Petrodel Resources Ltd, Adams v Cape Industries.',
                ],
                [
                    't' => 'Shareholder Protection & Minority Rights',
                    'f' => 's 994 (Unfair Prejudice), ss 260–264',
                    'd' => 'Foss v Harbottle, O’Neill v Phillips, Re Saul D Harrison & Sons plc, Ebrahimi v Westbourne Galleries.',
                ],
                [
                    't' => 'Mergers, Acquisitions & Takeovers',
                    'f' => 'UK Takeover Code + FSMA 2000 + Part 26 CA 2006',
                    'd' => 'Re Hellenic and General Trust Ltd, mandatory offer rules, and schemes of arrangement.',
                ],
                [
                    't' => 'Corporate Insolvency & Liquidation',
                    'f' => 'ss 213–214 Insolvency Act 1986 + CIGA 2020',
                    'd' => 'Re Spectrum Plus Ltd, BTI 2014 LLC v Sequana SA, wrongful trading and creditor duties.',
                ],
                [
                    't' => 'Corporate Criminal Liability & Fraud',
                    'f' => 'ECCTA 2023 + Criminal Justice Act 1993',
                    'd' => 'Failure to prevent fraud, senior manager liability, identification doctrine, and insider dealing.',
                ],
            ];
        }

        $isMath = str_contains(strtolower($subjectName), 'math') || str_contains(strtolower($page->slug), 'math');
        if ($isMath) {
            return [
                [
                    't' => 'Algebra',
                    'f' => 'x² − 5x + 6 = 0',
                    'd' => 'Simplifying and factorising, polynomial equations, inequalities, matrices and proof by induction.',
                ],
                [
                    't' => 'Calculus',
                    'f' => '∫ f(x) dx',
                    'd' => 'Limits, differentiation, integration by parts, substitution, series, multivariable calculus & ODEs.',
                ],
                [
                    't' => 'Geometry',
                    'f' => 'sin²θ + cos²θ = 1',
                    'd' => 'Coordinate geometry, vectors, circle theorems, transformations and GeoGebra diagrams.',
                ],
                [
                    't' => 'Trigonometry',
                    'f' => 'e^(iθ) = cosθ + i sinθ',
                    'd' => 'Identities, compound-angle formulae, solving equations, sine and cosine rules, and Fourier series.',
                ],
                [
                    't' => 'Probability & Statistics',
                    'f' => 'P(A | B) = [P(B|A)P(A)] / P(B)',
                    'd' => 'Distributions, conditional probability, Bayes’ theorem, hypothesis tests, regression in R, SPSS or Excel.',
                ],
                [
                    't' => 'Advanced & Applied Topics',
                    'f' => '∇²u = ∂u/∂t',
                    'd' => 'Group and ring theory, real & complex analysis, vector calculus, Runge-Kutta numerical methods, and LaTeX.',
                ],
            ];
        }

        return [
            [
                't' => "Core Principles of {$subjectName}",
                'f' => "Foundational Theories & Frameworks",
                'd' => "Essential principles, conceptual models and foundational literature in {$subjectName}.",
            ],
            [
                't' => "Advanced Theoretical Frameworks",
                'f' => "Critical Analysis & Models",
                'd' => "In-depth examination of contemporary models, critical debates, and structural paradigms.",
            ],
            [
                't' => "Applied Problem Solving & Case Studies",
                'f' => "Practical Applications",
                'd' => "Real-world scenario analysis, problem question structuring, and practical problem-solving methods.",
            ],
            [
                't' => "Research Methodologies & Synthesis",
                'f' => "Qualitative & Quantitative",
                'd' => "Systematic review techniques, empirical data evaluation, and academic synthesis standards.",
            ],
            [
                't' => "Contemporary Issues & Reform Debates",
                'f' => "Modern UK Curriculum Standards",
                'd' => "Emerging trends, policy reforms, and current developments affecting UK university briefs.",
            ],
            [
                't' => "Comparative Analysis & Critical Evaluation",
                'f' => "Cross-Disciplinary Perspectives",
                'd' => "Multi-perspective evaluation, comparative case analysis, and high-scoring critical judgements.",
            ],
        ];
    }

    private function extractTopicsFromContent(string $html): array
    {
        if (empty($html)) {
            return [];
        }

        $topics = [];

        // Match table rows: <tr><td>Sub-discipline</td><td>Sections</td><td>Cases</td></tr>
        if (preg_match_all('/<tr[^>]*>.*?<td[^>]*>(.*?)<\/td>.*?<td[^>]*>(.*?)<\/td>.*?<td[^>]*>(.*?)<\/td>.*?<\/tr>/is', $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $row) {
                $t = trim(strip_tags($row[1]));
                $f = trim(strip_tags($row[2]));
                $d = trim(strip_tags($row[3]));

                // Skip header rows
                if (str_contains(strtolower($t), 'sub-discipline') || str_contains(strtolower($t), 'area')) {
                    continue;
                }

                if (!empty($t) && strlen($t) < 120) {
                    $topics[] = [
                        't' => html_entity_decode($t, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                        'f' => html_entity_decode($f, ENT_QUOTES | ENT_HTML5, 'UTF-8') ?: "Key Statutory Provisions & Code",
                        'd' => html_entity_decode($d, ENT_QUOTES | ENT_HTML5, 'UTF-8') ?: "Detailed statutory analysis and judicial precedent application.",
                    ];
                }

                if (count($topics) >= 6) {
                    break;
                }
            }
        }

        return $topics;
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
