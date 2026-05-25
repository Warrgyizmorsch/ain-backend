<?php

namespace App\Http\Controllers;

use App\Models\Experts;
use Illuminate\Http\Request;
use Str;

class ExpertController extends Controller
{
    public function expert()
    {
        $data['title'] = 'Hire Uk Assignment Experts for Best Assignment Help Service Uk';
        $data['description'] = 'Meet our UK-based subject experts! Get highly qualified, accurate, and original assignment solutions designed to help you achieve top grades effortlessly.';
        
        $expert = Experts::all();
        return view('expert.expert', compact('data', 'expert'));
    }
    public function loadExperts(Request $request)   
    {
        $offset = $request->input('offset', 0);
        $experts = Experts::skip($offset)->take(9)->get();

        return response()->json([
            'html' => view('expert._expert_card_ajax', compact('experts'))->render(),
            'count' => $experts->count(),
        ]);
    }

  
    public function index()
    {
        $data['expert'] = Experts::all();

        return view('expert.expert-create', compact('data'));
    }

    public function create()
    {
        return view('expert.new-expert');
    }
 
  
    public function store(Request $request)
{
    // dd($request);
    try {
        // Validate request data
      $request->validate([
            'name' => 'required|string|max:255',
            'finish_order' => 'nullable|integer|min:0',
            'inprogress_order' => 'nullable|integer|min:0',
            'subject' => 'nullable|string|max:255',
            'services' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'skills' => 'nullable|array',
            'skills.*' => 'nullable|max:255',
            'helpus' => 'nullable|array',
            'helpus.*' => 'nullable|max:255',
            'customer_name' => 'nullable|array|min:1',
            'customer_name.*' => 'nullable|string|max:255',
            'customer_rating' => 'nullable|array|min:1',
            'customer_rating.*' => 'nullable|integer|min:1|max:5',
            'customer_review' => 'nullable|array|min:1',
            'customer_review.*' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5632',
            'success' => 'string|max:255',
            'MetaTag' => 'required|string|max:255',
            'Metadescription'=>'required|string|max:255',
 // Validate file upload
        ]);
    
        // Create a new expert instance
        $expert = new Experts();
        $expert->name = $request->name;
        $expert->slug = Str::slug($request->name);
        $expert->finish_order = $request->finish_order;
        $expert->inprogress_order = $request->inprogress_order;
        $expert->subject = $request->subject;
        $expert->service = $request->services;
        $expert->location = $request->location;
        $expert->content = $request->description;
        $expert->success = $request->success;
        // Assign JSON-stored fields
        $expert->skills = $request->skills;
        $expert->helpus = $request->helpus;
        $expert->meta_tag = $request->MetaTag;
        $expert->meta_description = $request->Metadescription;
    
        // Process customer reviews into a JSON object
        $customerReviews = [];
        foreach ($request->customer_name as $index => $name) {
            $customerReviews[] = [
                'name' => $name,
                'rating' => (int) $request->customer_rating[$index] ?? null, // Ensure integer
                'review' => $request->customer_review[$index] ?? null,
            ];
        }
        $expert->customer_review = json_encode($customerReviews, JSON_UNESCAPED_UNICODE); // Store clean JSON
    
         if ($request->hasFile('photo')) {
            $uploadedFile = $request->file('photo');
            $fileName = uniqid() . '_' . $uploadedFile->getClientOriginalName();
            $destinationPath = base_path('assets/media/blogthumbnail');
           
            $uploadedFile->move($destinationPath, $fileName);
            $expert->image = 'assets/media/blogthumbnail/' . $fileName;
        } 

        $expert->save();
        return redirect('new-expert')->with('success', 'Expert saved successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error updating expert: ' . $e->getMessage());
    } finally {
        // Optionally log errors or clean up resources if needed
    }
}

    

    public function destroy($id)
    {
        try {
            $expert = Experts::findOrFail($id); 
            $expert->delete();
            return redirect()->back()->with('success', 'Expert deleted successfully.');

        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Expert not found.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete the expert.');
        }
    }

    public function show ($id)
    {
        $expert = Experts::find($id);
        return view('expert.new-expert', compact('expert'));

    }

   public function update(Request $request, $id)
{
    try {
        // Validate the request data
      $request->validate([
            'name' => 'required|string|max:255',
            'finish_order' => 'nullable|string',
            'success' => 'string|max:255',
            'inprogress_order' => 'nullable|string',
            'subject' => 'nullable|string',
            'services' => 'nullable|string',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
            'skills' => 'nullable|array',
            'skills.*' => 'nullable|string',
            'helpus' => 'nullable|array',
            'helpus.*' => 'nullable|string',
            'customer_name' => 'nullable|array|min:1',
            'customer_name.*' => 'nullable|string|max:255',
            'customer_rating' => 'nullable|array|min:1',
            'customer_rating.*' => 'nullable|integer|min:1|max:5',
            'customer_review' => 'nullable|array|min:1',
            'customer_review.*' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5632',
            'MetaTag' => 'required|string|max:255',
            'Metadescription'=>'required|string|max:255'
            
        ]);
        // Find the expert by ID or fail with 404
        $expert = Experts::findOrFail($id);
        // Update the expert data from the request
        $expert->name = $request->name;
        $expert->slug = Str::slug($request->name);
        $expert->finish_order = $request->finish_order;
        $expert->inprogress_order = $request->inprogress_order;
        $expert->subject = $request->subject;
        $expert->service = $request->services;
        $expert->location = $request->location;
        $expert->content = $request->description;
        $expert->skills = $request->skills;
        $expert->success = $request->success;
        $expert->helpus = $request->helpus;
        $expert->meta_tag = $request->MetaTag;
        $expert->meta_description = $request->Metadescription;
        $customerReviews = [];
        foreach ($request->customer_name as $index => $name) {
            $customerReviews[] = [
                'name' => $name,
                'rating' => (int) $request->customer_rating[$index] ?? null, // Ensure integer
                'review' => $request->customer_review[$index] ?? null,
            ];
        }
        $expert->customer_review = json_encode($customerReviews, JSON_UNESCAPED_UNICODE); // Store clean JSON
    
        // Handle the file upload for the photo
         if ($request->hasFile('photo')) {
            $uploadedFile = $request->file('photo');
            $fileName = uniqid() . '_' . $uploadedFile->getClientOriginalName();
            $destinationPath = base_path('assets/media/blogthumbnail');
           
            $uploadedFile->move($destinationPath, $fileName);
            $expert->image = 'assets/media/blogthumbnail/' . $fileName;
         
         if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            } 

        } else if (!empty($expert->image) && file_exists(public_path($expert->image)) &&
            $expert->image !== 'assets/media/avatars/blank.png') {
            unlink(public_path($expert->image));
        }
        $expert->save();
        return redirect('new-expert')->with('success', 'Expert saved successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error updating expert: ' . $e->getMessage());
    } finally {
        // Optionally log errors or clean up resources if needed
    }
    
   

}
    

   public function expertProfile($slug)
{
    $expert = Experts::where('slug', $slug)->first();
    $skills = is_string($expert->skills) ? json_decode($expert->skills, true) : $expert->skills;
    $helpus = is_string($expert->helpus) ? json_decode($expert->helpus, true) : $expert->helpus;
    $customerReview = is_string($expert->customer_review) ? json_decode($expert->customer_review, true) : $expert->customer_review;
    $totalReviews = count($customerReview);
    $averageRating = $totalReviews > 0 ? array_sum(array_column($customerReview, 'rating')) / $totalReviews : 0;
    $data['title'] = $expert->meta_tag;
    $data['description'] = $expert->meta_description;
        
    return view('expert.experProfile', compact('data','expert', 'customerReview', 'helpus', 'skills', 'totalReviews', 'averageRating'));  // Make sure to use 'customerReview' here
}

    
}
