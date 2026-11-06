<?php

namespace App\Http\Controllers;

use App\Models\Experts;
use App\Models\PrimeServicePage;
use App\Models\PrimeSubject;
use App\Models\Review;
use App\Services\PrimeAssignmentSetupService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PrimeServicePageController extends Controller
{
    public function __construct()
    {
        PrimeAssignmentSetupService::ensureSetup();
    }

    /**
     * Highly optimized listing method for 200+ pages.
     * Selects only required columns, eager loads relations, and paginates.
     */
    public function index(Request $request)
    {
        $query = PrimeServicePage::query()
            ->select(['id', 'prime_subject_id', 'type', 'hero_heading', 'slug', 'is_published', 'created_at'])
            ->with(['primeSubject' => function ($q) {
                $q->select('id', 'name', 'slug');
            }]);

        // Keyword search (Hero heading or Slug)
        if ($request->filled('search')) {
            $keyword = trim((string) $request->input('search'));
            $query->where(function ($q) use ($keyword) {
                $q->where('hero_heading', 'like', "%{$keyword}%")
                  ->orWhere('slug', 'like', "%{$keyword}%");
            });
        }

        // Type filter (service, subject, city)
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // Prefix filter
        if ($request->filled('prefix_id')) {
            $query->where('prime_subject_id', (int) $request->input('prefix_id'));
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'published') {
                $query->where('is_published', true);
            } elseif ($status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $pages = $query->latest('id')->paginate(25)->withQueryString();

        $subjects = PrimeSubject::orderBy('name')->get(['id', 'name', 'slug']);

        return view('prime-service-pages.index', compact('pages', 'subjects'));
    }

    public function create()
    {
        return $this->form(new PrimeServicePage());
    }

    public function store(Request $request)
    {
        PrimeServicePage::create($this->validated($request));

        return redirect()->route('prime-service-pages.index')->with('success', 'Prime dynamic page created successfully.');
    }

    public function edit(PrimeServicePage $primeServicePage)
    {
        return $this->form($primeServicePage);
    }

    public function update(Request $request, PrimeServicePage $primeServicePage)
    {
        $primeServicePage->update($this->validated($request, $primeServicePage));

        return redirect()->route('prime-service-pages.index')->with('success', 'Prime dynamic page updated successfully.');
    }

    public function destroy(PrimeServicePage $primeServicePage)
    {
        $primeServicePage->delete();

        return back()->with('success', 'Prime dynamic page deleted successfully.');
    }

    public function preview(PrimeServicePage $primeServicePage)
    {
        return view('prime-service-pages.preview', [
            'page'    => $primeServicePage->load('primeSubject'),
            'experts' => $primeServicePage->selectedExperts(),
            'reviews' => $primeServicePage->selectedReviews(),
        ]);
    }

    private function form(PrimeServicePage $page)
    {
        return view('prime-service-pages.form', [
            'page'     => $page,
            'subjects' => PrimeSubject::where('is_active', true)->orderBy('name')->get(),
            'experts'  => Experts::orderBy('name')->get(),
            'reviews'  => Review::latest()->get(),
        ]);
    }

    private function validated(Request $request, ?PrimeServicePage $page = null): array
    {
        $slugRule = Rule::unique('prime_service_pages', 'slug');
        if ($page) {
            $slugRule->ignore($page->id);
        }

        $data = $request->validate([
            'prime_subject_id'      => ['nullable', 'exists:prime_subjects,id'],
            'type'                  => ['nullable', 'string', 'in:service,subject,city'],
            'slug'                  => ['required', 'string', 'max:180', $slugRule],
            'meta_title'            => ['required', 'string', 'max:255'],
            'meta_description'      => ['required', 'string', 'max:1000'],
            'hero_heading'          => ['required', 'string', 'max:255'],
            'hero_highlight'        => ['nullable', 'string', 'max:255'],
            'hero_content'          => ['required', 'string'],
            'section_two_heading'   => ['nullable', 'string', 'max:255'],
            'section_two_content'   => ['nullable', 'string'],
            'section_three_heading' => ['nullable', 'string', 'max:255'],
            'section_three_content' => ['nullable', 'string'],
            'why_heading'           => ['nullable', 'string', 'max:255'],
            'why_subheading'        => ['nullable', 'string', 'max:2000'],
            'why_items'             => ['nullable', 'array'],
            'why_items.*.icon'      => ['nullable', 'string', 'max:100'],
            'why_items.*.heading'   => ['nullable', 'string', 'max:255'],
            'why_items.*.content'   => ['nullable', 'string', 'max:2000'],
            'cta_content'           => ['nullable', 'string', 'max:2000'],
            'cta_button_label'      => ['nullable', 'string', 'max:100'],
            'cta_button_url'        => ['nullable', 'string', 'max:500'],
            'long_content'          => ['nullable', 'string'],
            'expert_ids'            => ['nullable', 'array'],
            'expert_ids.*'          => ['integer', 'exists:expert,id'],
            'review_ids'            => ['nullable', 'array'],
            'review_ids.*'          => ['integer', 'exists:review,id'],
            'faqs'                  => ['nullable', 'array'],
            'faqs.*.question'       => ['nullable', 'string', 'max:500'],
            'faqs.*.answer'         => ['nullable', 'string'],
        ]);

        $data['prime_subject_id'] = filled($data['prime_subject_id'] ?? null) ? (int) $data['prime_subject_id'] : null;
        $data['type'] = in_array($data['type'] ?? '', ['service', 'subject', 'city']) ? $data['type'] : 'service';

        $parts = explode('/', trim($data['slug'], '/'));
        $parts = array_filter(array_map(fn($part) => Str::slug($part), $parts));
        $data['slug'] = implode('/', $parts);

        $data['expert_ids'] = array_values($data['expert_ids'] ?? []);
        $data['review_ids'] = array_values($data['review_ids'] ?? []);
        $data['faqs'] = array_values(array_filter(
            $data['faqs'] ?? [],
            fn($faq) => filled($faq['question'] ?? null) && filled($faq['answer'] ?? null)
        ));
        $data['why_items'] = array_values(array_filter(
            $data['why_items'] ?? [],
            fn($item) => filled($item['heading'] ?? null) && filled($item['content'] ?? null)
        ));
        $data['is_published'] = $request->boolean('is_published', true);

        return $data;
    }
}

