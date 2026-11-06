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
            $type = strtolower($page->type ?? '');

            if (!in_array($type, ['service', 'subject', 'city'])) {
                $slug = trim($page->slug, '/');
                $firstSegment = strtolower(explode('/', $slug)[0] ?? '');
                $subjectSlug  = strtolower($page->primeSubject->slug ?? '');
                $subjectName  = strtolower($page->primeSubject->name ?? '');

                if (
                    in_array($firstSegment, ['city', 'cities']) ||
                    in_array($subjectSlug, ['city', 'cities']) ||
                    str_contains($firstSegment, 'city') ||
                    str_contains($subjectSlug, 'city') ||
                    str_contains($subjectName, 'city')
                ) {
                    $type = 'city';
                } elseif (
                    in_array($firstSegment, ['subject', 'subjects']) ||
                    in_array($subjectSlug, ['subject', 'subjects']) ||
                    str_contains($firstSegment, 'subject') ||
                    str_contains($subjectSlug, 'subject') ||
                    str_contains($subjectName, 'subject')
                ) {
                    $type = 'subject';
                } else {
                    $type = 'service';
                }
            }

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
     * Get details of a specific prime dynamic page by slug, title, or prefix.
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
            }
        }

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => "Prime page not found for slug '{$slug}'",
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
        $subjectName = $page->primeSubject?->name ?: $this->extractSubjectName($page);
        $pageType = $page->type ?: 'subject';

        // 1. Topics
        $topics = $page->topics;
        if (empty($topics) || !is_array($topics)) {
            $topics = $this->getDefaultTopics($page, $subjectName);
        }

        // 2. Why Choose Us Heading
        $whyChooseUsHeading = [
            'title'         => $page->why_heading ?: 'Why Students Choose',
            'highlightWord' => $page->why_subheading ?: "Our {$subjectName} Help",
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
                    'd' => 'Every formula, rule, and substitution is clearly written out so you can understand the exact method.',
                ],
                [
                    't' => 'IMA-affiliated specialists',
                    'd' => 'All briefs are solved by postgraduate and doctoral specialists with proven UK university experience.',
                ],
                [
                    't' => 'Double error-checked',
                    'd' => 'Every derivation, equation, and calculation is independently reviewed by a second specialist.',
                ],
                [
                    't' => 'LaTeX & MATLAB ready',
                    'd' => 'Delivered in professional LaTeX PDF, MATLAB script, Python notebook, or handwritten digital scan.',
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
                    'degree' => $exp->subject ? "Ph.D. / Specialist in {$exp->subject}" : 'Postgraduate Specialist',
                    'desc'   => $exp->content ?: ($exp->meta_description ?: 'Experienced academic specialist with 10+ years tutoring.'),
                    'rating' => '4.98',
                    'orders' => ($exp->finish_order ?: 1200) . '+',
                    'image'  => $exp->image ? url($exp->image) : '/images/home/why-choose/expert-1.webp',
                ];
            }
        }
        if (empty($expertsList)) {
            $expertsList = [
                [
                    'name'   => 'Dr. Alistair Finch',
                    'degree' => 'Ph.D. in Pure Mathematics (Warwick)',
                    'desc'   => 'IMA Member, specializes in Real Analysis, Differential Equations & Topology with 12+ years academic tutoring.',
                    'rating' => '4.98',
                    'orders' => '1,420+',
                    'image'  => '/images/home/why-choose/expert-1.webp',
                ],
                [
                    'name'   => 'Dr. Rebecca Thornton',
                    'degree' => 'Ph.D. in Applied Mathematics (Imperial)',
                    'desc'   => 'Fluid dynamics, numerical analysis, MATLAB & Python mathematical modelling specialist.',
                    'rating' => '4.95',
                    'orders' => '1,180+',
                    'image'  => '/images/home/why-choose/expert-2.webp',
                ],
                [
                    'name'   => 'David Sterling, M.Sc.',
                    'degree' => 'M.Sc. in Mathematical Statistics (Oxford)',
                    'desc'   => 'Probability distributions, regression models, SPSS, R syntax & stochastic processes specialist.',
                    'rating' => '4.96',
                    'orders' => '980+',
                    'image'  => '/images/home/why-choose/expert-3.webp',
                ],
            ];
        }

        // 5. Reviews
        $reviewsList = [];
        $selectedReviews = $page->selectedReviews();
        if ($selectedReviews->isNotEmpty()) {
            foreach ($selectedReviews as $rev) {
                $reviewsList[] = [
                    'category' => $rev->services_type ?: $subjectName,
                    'text'     => $rev->description ?: 'Clear step-by-step guidance provided. Highly recommended!',
                    'student'  => $rev->name ?: 'Student',
                    'course'   => $rev->location ?: 'UK University',
                    'grade'    => 'First Class',
                    'rating'   => (int) ($rev->customer_rating ?: 5),
                ];
            }
        }
        if (empty($reviewsList)) {
            $reviewsList = [
                [
                    'category' => $subjectName ?: 'Mathematics',
                    'text'     => "I was stuck on difficult coursework problems. The solution had every single intermediate step written out clearly in LaTeX. Scored 84% on my coursework!",
                    'student'  => 'Oliver B.',
                    'course'   => 'B.Sc. Mathematics, University of Manchester',
                    'grade'    => 'First Class',
                    'rating'   => 5,
                ],
                [
                    'category' => $subjectName ?: 'Mathematics',
                    'text'     => 'The Python scripts and plots were delivered 2 days ahead of my deadline. Perfectly commented code and clear explanations.',
                    'student'  => 'Charlotte W.',
                    'course'   => 'M.Eng. Mechanical Engineering, University of Leeds',
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
                        'type' => $sample->category?->name ?: $subjectName,
                        'w'    => number_format($sample->words_count ?: 3000),
                        'd'    => number_format((int) (($sample->words_count ?: 3000) * 0.75)),
                    ];
                }
            }
        } catch (\Throwable $e) {}

        if (empty($samplesList)) {
            $samplesList = [
                [
                    't'    => 'Second-Order Linear Non-Homogeneous Differential Equations Worked Solutions',
                    'type' => 'Calculus',
                    'w'    => '3,200',
                    'd'    => '2,450',
                ],
                [
                    't'    => 'Multivariate Regression & Residual Diagnostics in Python',
                    'type' => 'Statistics',
                    'w'    => '4,100',
                    'd'    => '3,120',
                ],
                [
                    't'    => 'Abstract Algebra: Sylow Theorems and Finite Group Classifications',
                    'type' => 'Pure Maths',
                    'w'    => '2,800',
                    'd'    => '1,890',
                ],
            ];
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
            $lowerSub = strtolower($subjectName);
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
            "{$subjectName} Assignment Help",
            "{$subjectName} Assignment Help UK",
            "{$subjectName} Coursework Help",
            'IMA Mathematicians UK',
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

        return [
            'slug'               => trim($page->slug, '/'),
            'pageType'           => $pageType,
            'subjectName'        => $subjectName,
            'title'              => $page->meta_title ?: $page->hero_heading,
            'h1'                 => $page->hero_heading,
            'h1Highlight'        => $page->hero_highlight ?: '',
            'sub'                => $page->hero_content,
            'brand'              => $page->brand ?: '#1E50E6',
            'brandSoft'          => $page->brand_soft ?: '#EBF0FD',
            'rating'             => '4.9',
            'ratingCount'        => '12,450+',
            'experienceYears'    => '10+',
            'discountPercentage' => '45%',
            'startingPrice'      => '£4.00',
            'originalPrice'      => '£9.00',
            'stats'              => $page->stats ?: [
                'delivered' => '4,800+',
                'experts'   => '120+',
                'rating'    => '4.9/5',
                'onTime'    => '96%',
            ],
            'topics'             => $topics,
            'whyChooseUsHeading' => $whyChooseUsHeading,
            'whyChooseUs'        => $whyChooseUs,
            'experts'            => $expertsList,
            'reviews'            => $reviewsList,
            'samples'            => $samplesList,
            'faqs'               => $faqsList,
            'seo'                => $seo,
            'contentHtml'        => $contentHtml,
            'page'               => $page, // backwards compatibility
        ];
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
