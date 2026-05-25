<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CategoyType;
use App\Models\Sample;
use App\Services\SchemaService;
use Illuminate\Http\Request;
use App\Models\SampleCategory;
use Illuminate\Tests\Integration\Database\EloquentHasManyThroughTest\Category;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\ExpertController;
use App\Models\Experts;
use Validator; // Ensure this package is installed and configured
use Illuminate\Support\Facades\DB;


class SampleController extends Controller
{

    function generateSchema($title, $description)
    {
        return json_encode([
            "@context" => "https://schema.org/",
            "@type" => "Product",
            "name" => 'Assignmennt in need',
            "image" => "https://www.assignnmentinneed.com/assets/media/layout/assignment_logo.webp",

            "offers" => [
                "@type" => "AggregateOffer",
                "url" => "https://www.assignnmentinneed.com/Offers",
                "priceCurrency" => "GBP",
                "lowPrice" => '7.99',
                "highPrice" => '9.99',
                "offerCount" => "6"

            ],
            "aggregateRating" => [
                "@type" => "AggregateRating",
                "ratingValue" => "4.5",
                "ratingCount" => "100"
            ],
            "url" => env('CANONICAL_URL', url()->current())
        ], JSON_UNESCAPED_SLASHES);
    }


    function artical($title, $description, $pdalishDate, $updalishDate)
    {
        return json_encode([
            "@context" => "http://schema.org",
            "@type" => "Article",
            "image" => "https://www.assignnmentinneed.com/assets/media/layout/assignment_logo.webp",


            "mainEntityOfPage" => [
                "@type" => "WebPage",
                "@id" => env('CANONICAL_URL', url()->current()) ?? ""
            ],
            "headline" => $title ?? "",
            "datePublished" => $pdalishDate ?? "",
            "dateModified" => $updalishDate ?? "",
            "author" => [
                "@type" => "Organization",
                "name" => "Assignmenntinneed",
                "url" => "https://www.assignnmentinneed.com",
            ],
            "publisher" => [
                "@type" => "Organization",
                "name" => 'Assignmenntinneed',

            ],
            "description" => $description
        ], JSON_UNESCAPED_SLASHES);
    }


    function BreadcrumbList(array $breadcrumbs): string
    {
        $breadcrumbSchema = [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => []
        ];

        foreach ($breadcrumbs as $position => $breadcrumb) {
            $breadcrumbSchema['itemListElement'][] = [
                "@type" => "ListItem",
                "position" => $position + 1,
                "name" => $breadcrumb['name'],
                "item" => $breadcrumb['url']
            ];
        }

        // Return as a JSON string
        return json_encode($breadcrumbSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }


    public function index()
    {
        $sampleCategory = SampleCategory::all();
        return view('sample.sampleCategory', compact('sampleCategory'));
    }


    public function indexpage()
    {
        $categories = SampleCategory::withCount('sample')
            ->having('sample_count', '>', 0)
            ->orderBy('name', 'asc')
            ->get()
            ->groupBy(function ($category) {
                return strtoupper(substr($category->name, 0, 1)); // Group by first letter
            });;

        $title = 'List of Free Assignments Samples and Examples for Students';
        $description = 'Download all types of free assignment samples for student assistance. Access university-level examples to guide your work and boost your academic performance.';
        $pdalishDate = '2024-11-28T07:52:13+00:00';
        $updalishDate = '2024-12-02T08:56:12+00:00';

        $data['schema'] = $this->generateSchema($title,  $description);
        $data['artical'] = $this->Artical($title, $description, $pdalishDate, $updalishDate);
        $data['title'] = $title;
        $data['description'] = $description;



        return view('sample.free-samples', compact('categories', 'data'));
    }

  public function categoryDeatails(Request $request, $title)
{
    $category = SampleCategory::where('name', $title)->firstOrFail();
    $categoryId = $category->id;
    $categoryName = $category->name;

    $limit = 5;
    $offset = $request->input('offset', 0);

    $samples = Sample::with('category')
        ->where('category', $categoryId)
        ->offset($offset)
        ->limit($limit)
        ->get();
    $expert = Experts::all();

     // AJAX response
    if ($request->ajax()) {
        return view('sample.partials.sample-list', compact('samples','categoryName'))->render();
    }

    // Meta and schema data
    $title = 'List of Free ' . $categoryName . ' Assignments Samples and Examples';
    $description = 'Get free ' . $categoryName . ' assignment samples to help you excel. Explore university-level examples designed to make your ' . $categoryName . ' tasks easier and more manageable';
    $pdalishDate = '2024-11-29T07:52:13+00:00';
    $updalishDate = '2024-12-03T08:56:12+00:00';
    $sampleschea = new SchemaService;
    $data['schema'] = $this->generateSchema($title, $description);
    $data['artical'] = $this->Artical($title, $description, $pdalishDate, $updalishDate);
    $data['BreadcrumbList'] = $sampleschea->BreadcrumbList([
        ['name' => 'Home', 'url' => 'https://www.assignmentinneed.com/'],
        ['name' => 'List of Free Assignments Samples and Examples for Students', 'url' => 'https://www.assignmentinneed.com/free-samples'],
        ['name' => 'List of Free ' . $categoryName . ' Assignments Samples and Examples', 'url' => 'https://www.assignmentinneed.com/free-samples/' . $categoryName]
    ]);
    $data['title'] = $title;
    $data['description'] = $description;

    $totalCount = Sample::where('category', $categoryId)->count();

    return view('sample.sampleCategorydetails', compact('samples', 'categoryName', 'data', 'expert', 'totalCount'));
}




    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            SampleCategory::create(['name' => $request->name]);

            return redirect()->back()->with('success', 'Data submitted successfully!');
        } catch (\Exception $e) {
            \Log::error("Error submitting data: " . $e->getMessage());

            return redirect()->back()->with('error', 'An error occurred while submitting the data.');
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $category = SampleCategory::findOrFail($id);
            $category->update(['name' => $request->name]);

            return redirect()->back()->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            \Log::error("Error updating category: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the category.');
        }
    }
    public function destroy($id)
    {
        try {
            $category = SampleCategory::findOrFail($id);
            $category->delete();

            return redirect()->back()->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            \Log::error("Error deleting category: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the category.');
        }
    }

    public function samplewrite()
    {
        $sampleCategory = SampleCategory::all();
        $type = CategoyType::all();
        return view('sample.writesample', compact('sampleCategory', 'type'));
    }

    public function storeService(Request $request)
    {
        $validatedData = $request->validate([
            'category' => 'required|integer',
            'editorContent' => 'required|string',
            'title' => 'required|string',
            'type' => 'required|integer',
            'meta_title' => 'required|string',
            'meta_description' => 'required|string',
        ]);

        // Get the editor content
        $editorContent = $validatedData['editorContent'];

        // Apply the CSS 'list-style: inside' to all <li> tags (no inline style)
        $editorContent = preg_replace_callback('/<li\b[^>]*>/i', function ($matches) {
            return '<li style="list-style: outside; color:black;margin-left: 45px;">'; // Add desired CSS for <li> elements
        }, $editorContent);

        // Generate a slug for the title
        $validatedData['slug'] = Str::slug($validatedData['title'], '-');

        // Insert data into the database
        Sample::create([
            'category' => $validatedData['category'],
            'content' => $editorContent,
            'title' => $validatedData['title'],
            'type_id' => $validatedData['type'],
            'slug' => $validatedData['slug'],
            'meta_title' => $validatedData['meta_title'],
            'meta_description' => $validatedData['meta_description'],
        ]);

        return redirect()->back()->with('success', 'Content saved successfully!');
    }



    public function sampleDeatails(Request $request, $title, $subject)
    {

        $sample = Sample::where('slug', $subject)->latest('id')->first();
        $category = SampleCategory::where('name', $title)->first();
        $categoryId = $category->id;
        $categoryName =  $category->name;
        // $recwent5Cat = SampleCategory::latest('id')->take(5)->get();
        // $categoryDetails = Sample::join('sampleCategory', 'sample.category', '=', 'sampleCategory.id')
        // ->select('sampleCategory.name as subject', 'sample.category')
        // ->get();


        $categoryDetails = SampleCategory::withCount('Sample')
        ->get()
        ->filter(function ($item) {
            return $item->sample_count > 0;
        })
        ->map(function ($item) {
            return [
                'subject' => $item->name,
                'category' => $item->id,
                'total_samples' => $item->sample_count
            ];
        })
        ;


        // dd($categoryDetails);  




        $samplesuggestion = Sample::with('category')->where('category', $categoryId)->get();

        $title = $sample->meta_title;
        $description = $sample->meta_description;
        $pdalishDate = $sample->created_at ?? now();
        $updalishDate = $sample->updated_at ?? now();
        $sampleschea = New SchemaService;

        $data['schema'] = $this->generateSchema($title,  $description);
        $data['artical'] = $this->Artical($title, $description, $pdalishDate, $updalishDate);
        $data['BreadcrumbList'] = $sampleschea->BreadcrumbList([
            ['name' => 'Home', 'url' => 'https://www.assignnmentinneed.com'],
            ['name' => 'List of Free Assignments Samples and Examples for Students', 'url' => 'https://www.assignnmentinneed.com/
            // '],
            ['name' => 'List of Free Accounting Assignments Samples and Examples', 'url' => 'https://www.assignnmentinneed.com/free-samples/' . $categoryName],
            ['name' => $sample->title, 'url' => 'https://www.assignnmentinneed.com/free-samples/' . $categoryName . '/' . $sample->slug]
        ]);
        $data['title'] = $title;
        $data['description'] = $description;

        return view('sample.sampleDetails2', compact('sample', 'samplesuggestion', 'categoryName', 'data','categoryDetails' ));
    }

    public function sample_listt(Request $request)
    {
        // Retrieve the filter parameters from the request
        $title = $request->input('title');
        $type = $request->input('type');

        // Initialize the query builder for the Blog model
        $query = Sample::query();

        // Apply the title filter if provided
        if ($title) {
            $query->where('title', 'like', '%' . $title . '%');
        }

        // Apply the type filter if provided
        if ($type) {
            $query->where('type', $type);
        }

        // Order the results by creation date and paginate
        $data['sample'] = $query->orderBy('created_at', 'desc')->paginate(20);

        // Pass the filtered blog list to the view
        return view("blog-sample.sample-list", compact('data'));
    }



    public function downloadSample($slug)
    {
        // Fetch the Sample record based on slug
        $sample = Sample::where('slug', $slug)->firstOrFail();

        // Generate PDF with title and content
        $pdf = Pdf::loadView('pdf.sample', ['sample' => $sample]);

        // Return the PDF as a response that triggers an automatic download
        return response()->streamDownload(
            fn() => print($pdf->output()), // Stream the PDF content
            $sample->title . '.pdf',        // The filename of the downloaded file
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $sample->title . '.pdf"',
            ]
        );
    }


    // routing for sample by navneet

    public function samplesShows(Request $request)
    {
        // Retrieve the filter parameters from the request
        $title = $request->input('title');
        $category = $request->input('category');

        // Initialize the query builder for the Blog model
        $query = Sample::with('categotyData', 'type');

        // Apply the title filter if provided
        if ($title) {
            $query->where('title', 'like', '%' . $title . '%');
        }

        // Apply the type filter if provided
        // if ($category) {
        //     $query->where('category', $category);
        // }
        if (!empty($category)) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('name', $category) // Filter by category ID
                    ->orWhere('name', 'like', '%' . $category . '%'); // Or by category name
            });
        }
        $data['samples'] = $query->get();


        // echo '<pre>'; print_r($data); exit;

        return view('sample.sample-list', compact('data'));
    }



    public function samplesUpades($id)
    {
        $sampleCategory = SampleCategory::all();
        $type = CategoyType::all();
        $sample = Sample::find($id);

        // echo $sample ;

        return view('sample.writesample', compact('sampleCategory', 'sample', 'type'));
    }


    public function Sampleupdate(Request $req, $id)
    {
        // Validate the incoming request data
        $validatedData = $req->validate([
            'category' => 'required|integer',
            'editorContent' => 'required|string',
            'title' => 'required|string',
            'type' => 'required|integer',
            'meta_title' => 'required|string',
            'meta_description' => 'required|string',
        ]);

        $editorContent = $validatedData['editorContent'];

        // Apply the CSS 'list-style: inside' to all <li> tags (no inline style)
        $editorContent = preg_replace_callback('/<li\b[^>]*>/i', function ($matches) {
            return '<li style="list-style: outside; color:black; margin-left:45px">'; // Add desired CSS for <li> elements
        }, $editorContent);
        $validatedData['slug'] = Str::slug($validatedData['title'], '-');

        // Find the record by ID and update it
        $sample = Sample::findOrFail($id);
        $sample->update([
            'category' => $validatedData['category'],
            'content' =>  $editorContent,
            'title' => $validatedData['title'],
            'type_id' => $validatedData['type'],
            'slug' => $validatedData['slug'],
            'meta_title' => $validatedData['meta_title'],
            'meta_description' => $validatedData['meta_description'],
        ]);

        // Redirect back with a success message
        return redirect('/samples')->with('success', 'Content updated successfully!');
    }


    public function destroySample($id)

    {

        $sample = Sample::find($id);
        $sample->delete();


        return redirect()->back()->with('success', 'Sample deleted successfully');
    }

    public function indexType()
    {

        $CategoryType = CategoyType::orderBy('id', 'desc')->get();
        // echo '<pre>'; print_r($CategoryType); exit;
        return view("sample.categorytype", compact('CategoryType'));
    }

    public function sampleTypeStore(Request $req)
    {
        $req->validate([
            'name' => 'required|string|max:255',
        ]);
        $type =  new CategoyType();


        try {
            $type->name = $req->input('name');
            $type->save();
            return redirect()->back()->with('success', 'Data submitted successfully.');
        } catch (\Exception $e) {
            \Log::error("Error submitting data: {$e->getMessage()}");
            return redirect()->back()->with('error', 'An error occurred while submitting the data.');
        }
        //    $req->validate([
        //     'name'=> 'required'
        //    ]);

        //     $type =  new CategoyType();
        //     $type->name = $req->input('name');
        //     $type->save();
        //     return redirect()->back()->with('sucess', 'Sample Type Save Succesfully');
    }



    public function sampleTypeUpdate(Request $req, $id)
    {
        $type =  CategoyType::find($id);

        try {
            $type->name = $req->input('name');
            $type->save();
            return redirect()->back()->with('success', 'Sample Type Updated Succesfully.');
        } catch (\Exception $e) {
            \Log::error("Error submitting data: {$e->getMessage()}");
            return redirect()->back()->with('error', 'An error occurred while submitting the data.');
        }

        // $type =  CategoyType::find($id);
        // $type->name = $req->input('name');
        // $type->save();
        // return redirect()->back()->with('sucess', 'Sample Type Updated Succesfully');

    }


    public function destroysampleType($id)

    {
        $type = CategoyType::find($id);
        try {

            $type->delete();
            return redirect()->back()->with('success', 'Sample Type deleted Succesfully.');
        } catch (\Exception $e) {
            \Log::error("Error submitting data: {$e->getMessage()}");
            return redirect()->back()->with('error', 'An error occurred while submitting the data.');
        }

        // $type = CategoyType::find($id);
        // $type->delete();
        // return redirect()->back()->with('sucess', 'Sample Type deleted Succesfully');

    }
    public function fetchSamples(Request $request, $categoryName)
    {
        $sample = Sample::where('category', $categoryName)->paginate(5);

        if ($request->ajax()) {
            return view('sample.sampleCategorydetails', compact('sample', 'categoryName'))->render();
        }

        return view('sample.sampleCategorydetails', compact('sample', 'categoryName'));
    }
}
