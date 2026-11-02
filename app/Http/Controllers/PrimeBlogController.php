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
            if ($primeBlog->image && file_exists(public_path($primeBlog->image))) {
                @unlink(public_path($primeBlog->image));
            }
            $data['image'] = $this->uploadImage($request->file('image_file'), $data['slug']);
        }

        $primeBlog->update($data);

        return redirect()->route('prime-blogs.index')->with('success', 'Prime blog post updated successfully.');
    }

    public function destroy(PrimeBlog $primeBlog)
    {
        if ($primeBlog->image && file_exists(public_path($primeBlog->image))) {
            @unlink(public_path($primeBlog->image));
        }

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
        $dir = public_path('assets/media/prime/blogs');
        if (!file_exists($dir)) {
            @mkdir($dir, 0755, true);
        }

        $ext = $file->getClientOriginalExtension() ?: 'png';
        $filename = $slug . '-' . time() . '.' . strtolower($ext);
        $file->move($dir, $filename);

        return 'assets/media/prime/blogs/' . $filename;
    }
}

