<?php

namespace App\Http\Controllers;

use App\Models\Experts;
use App\Models\Review;
use App\Models\ServicePage;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServicePageController extends Controller
{
    public function index()
    {
        return view('service-pages.index', ['pages' => ServicePage::with('subject')->latest()->get()]);
    }

    public function create()
    {
        return $this->form(new ServicePage());
    }

    public function store(Request $request)
    {
        ServicePage::create($this->validated($request));
        return redirect()->route('service-pages.index')->with('success', 'Dynamic service page created.');
    }

    public function edit(ServicePage $servicePage)
    {
        return $this->form($servicePage);
    }

    public function update(Request $request, ServicePage $servicePage)
    {
        $servicePage->update($this->validated($request, $servicePage));
        return redirect()->route('service-pages.index')->with('success', 'Dynamic service page updated.');
    }

    public function destroy(ServicePage $servicePage)
    {
        $servicePage->delete();
        return back()->with('success', 'Dynamic service page deleted.');
    }

    public function preview(ServicePage $servicePage)
    {
        return view('service-pages.preview', [
            'page' => $servicePage->load('subject'),
            'experts' => $servicePage->selectedExperts(),
            'reviews' => $servicePage->selectedReviews(),
        ]);
    }

    private function form(ServicePage $page)
    {
        return view('service-pages.form', [
            'page' => $page,
            'subjects' => Subject::where('is_active', true)->orderBy('name')->get(),
            'experts' => Experts::orderBy('name')->get(),
            'reviews' => Review::latest()->get(),
        ]);
    }

    private function validated(Request $request, ?ServicePage $page = null): array
    {
        $slugRule = Rule::unique('service_pages', 'slug');
        if ($page) $slugRule->ignore($page->id);

        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'slug' => ['required', 'string', 'max:180', $slugRule],
            'meta_title' => ['required', 'string', 'max:255'],
            'meta_description' => ['required', 'string', 'max:1000'],
            'hero_heading' => ['required', 'string', 'max:255'],
            'hero_highlight' => ['nullable', 'string', 'max:255'],
            'hero_content' => ['required', 'string'],
            'section_two_heading' => ['nullable', 'string', 'max:255'],
            'section_two_content' => ['nullable', 'string'],
            'section_three_heading' => ['nullable', 'string', 'max:255'],
            'section_three_content' => ['nullable', 'string'],
            'why_heading' => ['nullable', 'string', 'max:255'],
            'why_subheading' => ['nullable', 'string', 'max:2000'],
            'why_items' => ['nullable', 'array'],
            'why_items.*.icon' => ['nullable', 'string', 'max:100'],
            'why_items.*.heading' => ['nullable', 'string', 'max:255'],
            'why_items.*.content' => ['nullable', 'string', 'max:2000'],
            'cta_content' => ['nullable', 'string', 'max:2000'],
            'cta_button_label' => ['nullable', 'string', 'max:100'],
            'cta_button_url' => ['nullable', 'string', 'max:500'],
            'expert_ids' => ['nullable', 'array'],
            'expert_ids.*' => ['integer', 'exists:expert,id'],
            'review_ids' => ['nullable', 'array'],
            'review_ids.*' => ['integer', 'exists:review,id'],
            'faqs' => ['nullable', 'array'],
            'faqs.*.question' => ['nullable', 'string', 'max:500'],
            'faqs.*.answer' => ['nullable', 'string'],
        ]);

        $data['slug'] = Str::slug($data['slug']);
        $data['expert_ids'] = array_values($data['expert_ids'] ?? []);
        $data['review_ids'] = array_values($data['review_ids'] ?? []);
        $data['faqs'] = array_values(array_filter($data['faqs'] ?? [], fn ($faq) => filled($faq['question'] ?? null) && filled($faq['answer'] ?? null)));
        $data['why_items'] = array_values(array_filter($data['why_items'] ?? [], fn ($item) => filled($item['heading'] ?? null) && filled($item['content'] ?? null)));
        $data['is_published'] = $request->boolean('is_published');
        return $data;
    }
}
