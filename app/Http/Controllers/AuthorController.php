<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthorController extends Controller
{

    // ======================================
    // LIST
    // ======================================

    public function index()
    {
        $authors = DB::table('author')
            ->latest()
            ->get();

        return view(
            'author.index',
            compact('authors')
        );
    }

    // ======================================
    // STORE
    // ======================================

    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required',

            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp',

            'description' => 'nullable'
        ]);

        $photo = null;

        // ======================================
        // IMAGE UPLOAD
        // ======================================

        if ($request->hasFile('photo')) {

            $file = $request->file('photo');

            $imageName = time() . '.' . $file->getClientOriginalExtension();

            // Folder Create If Not Exists
            $destinationPath = public_path('author');

            if (!file_exists($destinationPath)) {

                mkdir($destinationPath, 0777, true);
            }

            // Upload
            $file->move($destinationPath, $imageName);

            // Save Path In DB
            $photo = 'public/author/' . $imageName;
        }

        // ======================================
        // INSERT
        // ======================================

        DB::table('author')->insert([

            'name' => $request->name,

            'photo' => $photo,

            'description' => $request->description,

            'created_at' => now(),

            'updated_at' => now()
        ]);

        return redirect()
            ->back()
            ->with('success', 'Author Added Successfully');
    }

    // ======================================
    // UPDATE
    // ======================================

    public function update(Request $request, $id)
    {
        $author = DB::table('author')
            ->where('id', $id)
            ->first();

        $request->validate([

            'name' => 'required',

            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp',

            'description' => 'nullable'
        ]);

        $photo = $author->photo;

        // ======================================
        // IMAGE UPDATE
        // ======================================

        if ($request->hasFile('photo')) {

            // Delete Old Image
            if (
                $author->photo &&
                file_exists(public_path($author->photo))
            ) {

                unlink(public_path($author->photo));
            }

            $file = $request->file('photo');

            $imageName = time() . '.' . $file->getClientOriginalExtension();

            $destinationPath = public_path('author');

            if (!file_exists($destinationPath)) {

                mkdir($destinationPath, 0777, true);
            }

            $file->move($destinationPath, $imageName);

            // Save New Path
            $photo = 'public/author/' . $imageName;
        }

        // ======================================
        // UPDATE
        // ======================================

        DB::table('author')
            ->where('id', $id)
            ->update([

                'name' => $request->name,

                'photo' => $photo,

                'description' => $request->description,

                'updated_at' => now()
            ]);

        return redirect()
            ->back()
            ->with('success', 'Author Updated Successfully');
    }

    // ======================================
    // DELETE
    // ======================================

    public function destroy($id)
    {
        $author = DB::table('author')
            ->where('id', $id)
            ->first();

        // ======================================
        // DELETE IMAGE
        // ======================================

        if (
            $author->photo &&
            file_exists(public_path($author->photo))
        ) {

            unlink(public_path($author->photo));
        }

        // ======================================
        // DELETE RECORD
        // ======================================

        DB::table('author')
            ->where('id', $id)
            ->delete();

        return redirect()
            ->back()
            ->with('success', 'Author Deleted Successfully');
    }
}