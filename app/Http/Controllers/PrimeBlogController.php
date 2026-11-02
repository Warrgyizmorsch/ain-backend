<?php

namespace App\Http\Controllers;

use App\Models\PrimeBlog;
use App\Services\PrimeAssignmentSetupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PrimeBlogController extends Controller
{
    public function __construct()
    {
        PrimeAssignmentSetupService::ensureSetup();
    }

    /**
     * Optimized listing method for Prime Blogs.
     */
    public function index(Request $request)
    {
        $query = PrimeBlog::query()
            ->select(['id', 'title', 'slug', 'image', 'category', 'author_name', 'is_published', 'created_at']);

        // Search by title or slug
        if ($request->filled('search')) {
            $keyword = trim((string) $request->input('search'));
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('slug', 'like', "%{$keyword}%");
            });
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

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $blogs = $query->latest('id')->paginate(25)->withQueryString();

        $categories = PrimeBlog::whereNotNull('category')->where('category', '!=', '')->distinct()->pluck('category');

        return view('prime-blogs.index', compact('blogs', 'categories'));
    }

    public function create()
    {
        return $this->form(new PrimeBlog());
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image_file')) {
            $data['image'] = $this->uploadImage($request->file('image_file'), $data['slug']);
        }

        PrimeBlog::create($data);

        return redirect()->route('prime-blogs.index')->with('success', 'Prime blog post created successfully.');
    }

    public function edit(PrimeBlog $primeBlog)
    {
        return $this->form($primeBlog);
    }

    public function update(Request $request, PrimeBlog $primeBlog)
    {
        $data = $this->validated($request, $primeBlog);

        if ($request->hasFile('image_file')) {
            $this->deleteFileFromAllPaths($primeBlog->image);
            $data['image'] = $this->uploadImage($request->file('image_file'), $data['slug']);
        }

        $primeBlog->update($data);

        return redirect()->route('prime-blogs.index')->with('success', 'Prime blog post updated successfully.');
    }

    public function destroy(PrimeBlog $primeBlog)
    {
        $this->deleteFileFromAllPaths($primeBlog->image);

        $primeBlog->delete();

        return back()->with('success', 'Prime blog post deleted successfully.');
    }

    public function preview(PrimeBlog $primeBlog)
    {
        return view('prime-blogs.preview', ['blog' => $primeBlog]);
    }

    private function form(PrimeBlog $blog)
    {
        $authors = [];
        if (\Illuminate\Support\Facades\Schema::hasTable('author')) {
            $authors = DB::table('author')->select('id', 'name')->orderBy('name')->get();
        }

        return view('prime-blogs.form', compact('blog', 'authors'));
    }

    private function validated(Request $request, ?PrimeBlog $blog = null): array
    {
        $slugRule = Rule::unique('prime_blogs', 'slug');
        if ($blog) {
            $slugRule->ignore($blog->id);
        }

        $data = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['required', 'string', 'max:180', $slugRule],
            'content'          => ['required', 'string'],
            'image'            => ['nullable', 'string', 'max:255'],
            'image_file'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'author_id'        => ['nullable', 'integer'],
            'author_name'      => ['nullable', 'string', 'max:150'],
            'category'         => ['nullable', 'string', 'max:100'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'meta_keywords'    => ['nullable', 'string', 'max:500'],
            'faqs'             => ['nullable', 'array'],
            'faqs.*.question'  => ['nullable', 'string', 'max:500'],
            'faqs.*.answer'    => ['nullable', 'string'],
        ]);

        $data['slug'] = Str::slug(trim($data['slug'], '/'), '-');

        // Resolve author name if author_id is provided
        if (!empty($data['author_id']) && empty($data['author_name']) && \Illuminate\Support\Facades\Schema::hasTable('author')) {
            $author = DB::table('author')->where('id', $data['author_id'])->first();
            if ($author) {
                $data['author_name'] = $author->name;
            }
        }

        $data['faqs'] = array_values(array_filter(
            $data['faqs'] ?? [],
            fn($faq) => filled($faq['question'] ?? null) && filled($faq['answer'] ?? null)
        ));

        $data['is_published'] = $request->boolean('is_published', true);

        return $data;
    }

    private function uploadImage($file, string $slug): string
    {
        $cleanSlug = Str::slug(substr(preg_replace('/[^a-zA-Z0-9_-]/', '', $slug), 0, 50)) ?: 'prime-blog';
        $ext = $file->getClientOriginalExtension() ?: 'png';
        $filename = $cleanSlug . '-' . time() . '.' . strtolower($ext);

        $this->saveUploadedFileToAllPaths($file, 'assets/media/prime/blogs', $filename);

        return 'assets/media/prime/blogs/' . $filename;
    }

    private function saveUploadedFileToAllPaths($uploadedFile, string $subPath, string $fileName): void
    {
        $cleanSubPath = trim($subPath, '/\\');
        $targetDirs = array_unique([
            base_path($cleanSubPath),
            public_path($cleanSubPath),
            rtrim(dirname(base_path()), '/\\') . '/public_html/' . $cleanSubPath,
        ]);

        $savedPath = null;
        foreach ($targetDirs as $dir) {
            if (!file_exists($dir)) {
                @mkdir($dir, 0755, true);
            }
            if (is_dir($dir)) {
                $targetFile = rtrim($dir, '/\\') . '/' . $fileName;
                if (!$savedPath) {
                    $uploadedFile->move($dir, $fileName);
                    $savedPath = $targetFile;
                } else {
                    @copy($savedPath, $targetFile);
                }
            }
        }
    }

    private function deleteFileFromAllPaths(?string $relativeFilePath): void
    {
        if (empty($relativeFilePath)) {
            return;
        }

        $cleanPath = ltrim($relativeFilePath, '/\\');
        $subPath = dirname($cleanPath);
        $fileName = basename($cleanPath);

        $targetDirs = array_unique([
            base_path($subPath),
            public_path($subPath),
            rtrim(dirname(base_path()), '/\\') . '/public_html/' . $subPath,
        ]);

        foreach ($targetDirs as $dir) {
            $targetFile = rtrim($dir, '/\\') . '/' . $fileName;
            if (file_exists($targetFile)) {
                @unlink($targetFile);
            }
        }
    }
}
