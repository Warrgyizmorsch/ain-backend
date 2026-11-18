<?php

namespace App\Http\Controllers;

use App\Models\PrimeExpert;
use App\Models\PrimeServicePage;
use App\Services\PrimeAssignmentSetupService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PrimeExpertController extends Controller
{
    private const IMAGE_DIR = 'assets/media/prime/experts';

    public function __construct()
    {
        PrimeAssignmentSetupService::ensureSetup();
    }

    public function index()
    {
        $experts = PrimeExpert::latest()->get();

        return view('prime-experts.index', compact('experts'));
    }

    public function create()
    {
        return view('prime-experts.form', $this->formData());
    }

    public function store(Request $request)
    {
        try {
            $data = $this->validateRequest($request);

            $expert = new PrimeExpert();
            $this->fill($expert, $request, $data);

            if ($request->hasFile('photo')) {
                $expert->image = $this->uploadImage($request->file('photo'));
            }

            $expert->save();

            return redirect()->route('prime-experts.index')->with('success', 'Prime expert saved successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error saving prime expert: ' . $e->getMessage());
        }
    }

    public function edit(PrimeExpert $primeExpert)
    {
        return view('prime-experts.form', $this->formData($primeExpert));
    }

    public function update(Request $request, PrimeExpert $primeExpert)
    {
        try {
            $data = $this->validateRequest($request);

            $this->fill($primeExpert, $request, $data);

            if ($request->hasFile('photo')) {
                $this->deleteImage($primeExpert->image);
                $primeExpert->image = $this->uploadImage($request->file('photo'));
            } elseif ($request->boolean('avatar_remove')) {
                $this->deleteImage($primeExpert->image);
                $primeExpert->image = null;
            }

            $primeExpert->save();

            return redirect()->route('prime-experts.index')->with('success', 'Prime expert updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error updating prime expert: ' . $e->getMessage());
        }
    }

    public function destroy(PrimeExpert $primeExpert)
    {
        $this->deleteImage($primeExpert->image);
        $primeExpert->delete();

        return redirect()->back()->with('success', 'Prime expert deleted successfully.');
    }

    /**
     * Subjects and services come only from Prime dynamic pages (type = subject / service).
     */
    private function formData(?PrimeExpert $expert = null): array
    {
        $pages = PrimeServicePage::whereIn('type', ['subject', 'service'])
            ->orderBy('hero_heading')
            ->get(['type', 'hero_heading']);

        $subjects = $pages->where('type', 'subject')->pluck('hero_heading')->filter()->unique()->values();
        $services = $pages->where('type', 'service')->pluck('hero_heading')->filter()->unique()->values();

        return compact('expert', 'subjects', 'services');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'image_alt' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5632',
            'finish_order' => 'nullable|string|max:50',
            'inprogress_order' => 'nullable|string|max:50',
            'success' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'services' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'skills' => 'nullable|array',
            'skills.*' => 'nullable|string|max:255',
            'helpus' => 'nullable|array',
            'helpus.*' => 'nullable|string|max:255',
            'customer_name' => 'nullable|array',
            'customer_name.*' => 'nullable|string|max:255',
            'customer_rating' => 'nullable|array',
            'customer_rating.*' => 'nullable|integer|min:1|max:5',
            'customer_review' => 'nullable|array',
            'customer_review.*' => 'nullable|string|max:1000',
            'MetaTag' => 'required|string|max:255',
            'Metadescription' => 'required|string|max:255',
        ], [
            'image_alt.required' => 'Image alt text is required.',
        ]);
    }

    private function fill(PrimeExpert $expert, Request $request, array $data): void
    {
        $expert->name = trim($data['name']);
        $expert->slug = Str::slug($data['name']);
        $expert->image_alt = trim($data['image_alt']);
        $expert->finish_order = $request->input('finish_order');
        $expert->inprogress_order = $request->input('inprogress_order');
        $expert->success = $request->filled('success') ? trim($request->input('success')) : null;
        $expert->subject = $request->input('subject');
        $expert->service = $request->input('services');
        $expert->location = $request->input('location');
        $expert->content = $request->input('description');
        $expert->skills = array_values(array_filter((array) $request->input('skills', []), 'filled'));
        $expert->helpus = array_values(array_filter((array) $request->input('helpus', []), 'filled'));
        $expert->meta_tag = $data['MetaTag'];
        $expert->meta_description = $data['Metadescription'];

        $reviews = [];
        foreach ((array) $request->input('customer_name', []) as $index => $name) {
            $review = $request->input("customer_review.$index");
            if (blank($name) && blank($review)) {
                continue;
            }
            $reviews[] = [
                'name' => $name,
                'rating' => (int) ($request->input("customer_rating.$index") ?: 5),
                'review' => $review,
            ];
        }
        $expert->customer_review = $reviews;
    }

    private function uploadImage($file): string
    {
        $fileName = uniqid() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '-', $file->getClientOriginalName());
        $subPath = self::IMAGE_DIR;

        $targetDirs = array_unique([
            base_path($subPath),
            public_path($subPath),
            rtrim(dirname(base_path()), '/\\') . '/public_html/' . $subPath,
        ]);

        $savedPath = null;
        foreach ($targetDirs as $dir) {
            if (!file_exists($dir)) {
                @mkdir($dir, 0755, true);
            }
            if (!is_dir($dir)) {
                continue;
            }
            $targetFile = rtrim($dir, '/\\') . '/' . $fileName;
            if (!$savedPath) {
                $file->move($dir, $fileName);
                $savedPath = $targetFile;
            } else {
                @copy($savedPath, $targetFile);
            }
        }

        return $subPath . '/' . $fileName;
    }

    private function deleteImage(?string $image): void
    {
        if (empty($image) || !str_starts_with($image, self::IMAGE_DIR)) {
            return;
        }

        $fileName = basename($image);
        $targetDirs = array_unique([
            base_path(self::IMAGE_DIR),
            public_path(self::IMAGE_DIR),
            rtrim(dirname(base_path()), '/\\') . '/public_html/' . self::IMAGE_DIR,
        ]);

        foreach ($targetDirs as $dir) {
            $targetFile = rtrim($dir, '/\\') . '/' . $fileName;
            if (file_exists($targetFile)) {
                @unlink($targetFile);
            }
        }
    }
}
