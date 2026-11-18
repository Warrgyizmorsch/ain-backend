<?php

namespace App\Http\Controllers;

use App\Models\PrimeReview;
use App\Models\PrimeServicePage;
use App\Services\PrimeAssignmentSetupService;
use Illuminate\Http\Request;

class PrimeReviewController extends Controller
{
    public const LOCATIONS = [
        'Uk', 'London', 'Birmingham', 'Manchester', 'AU', 'Sydney', 'Melbourne', 'Barcelona', 'CANADA', 'UAE', 'Spain',
        'Liverpool', 'Leeds', 'Newcastle upon Tyne', 'Sheffield', 'Bristol', 'Nottingham', 'Leicester', 'Southampton',
        'Portsmouth', 'Cambridge', 'Oxford', 'Edinburgh', 'Glasgow', 'Aberdeen',
    ];

    public function __construct()
    {
        PrimeAssignmentSetupService::ensureSetup();
    }

    public function index()
    {
        $reviews = PrimeReview::latest()->get();

        return view('prime-reviews.index', compact('reviews'));
    }

    public function create()
    {
        return view('prime-reviews.form', $this->formData());
    }

    public function store(Request $request)
    {
        PrimeReview::create($this->validated($request));

        return redirect()->back()->with('success', 'Prime review submitted successfully!');
    }

    public function edit(PrimeReview $primeReview)
    {
        return view('prime-reviews.form', $this->formData($primeReview));
    }

    public function update(Request $request, PrimeReview $primeReview)
    {
        $primeReview->update($this->validated($request));

        return redirect()->route('prime-reviews.index')->with('success', 'Prime review updated successfully!');
    }

    public function destroy(PrimeReview $primeReview)
    {
        $primeReview->delete();

        return redirect()->back()->with('success', 'Prime review deleted successfully!');
    }

    /**
     * Service dropdown comes only from Prime dynamic pages of type "service".
     */
    private function formData(?PrimeReview $review = null): array
    {
        $services = PrimeServicePage::where('type', 'service')
            ->orderBy('hero_heading')
            ->pluck('hero_heading')
            ->filter()
            ->unique()
            ->values();

        $locations = self::LOCATIONS;

        return compact('review', 'services', 'locations');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'deadline' => ['nullable', 'string', 'max:50'],
            'submission_date' => ['nullable', 'date'],
            'services_type' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'customer_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'description' => ['nullable', 'string', 'max:5000'],
            'review_reply' => ['nullable', 'string', 'max:5000'],
        ]);
    }
}
