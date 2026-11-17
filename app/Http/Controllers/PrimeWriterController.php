<?php

namespace App\Http\Controllers;

use App\Models\PrimeWriter;
use App\Services\PrimeAssignmentSetupService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PrimeWriterController extends Controller
{
    public function __construct()
    {
        PrimeAssignmentSetupService::ensureSetup();
    }

    public function index()
    {
        $writers = PrimeWriter::orderBy('name')->get();

        return view('prime-writers.index', compact('writers'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'name' => is_string($request->input('name')) ? trim($request->input('name')) : $request->input('name'),
            'email' => is_string($request->input('email')) ? strtolower(trim($request->input('email'))) : $request->input('email'),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:prime_writers,email'],
        ]);

        PrimeWriter::create($data);

        return back()->with('success', 'Prime writer created successfully.');
    }

    public function update(Request $request, PrimeWriter $primeWriter)
    {
        $request->merge([
            'name' => is_string($request->input('name')) ? trim($request->input('name')) : $request->input('name'),
            'email' => is_string($request->input('email')) ? strtolower(trim($request->input('email'))) : $request->input('email'),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('prime_writers', 'email')->ignore($primeWriter->id),
            ],
            'is_active' => ['required', 'boolean'],
        ]);

        $primeWriter->update($data);

        return back()->with('success', 'Prime writer updated successfully.');
    }

    public function destroy(PrimeWriter $primeWriter)
    {
        $primeWriter->update(['is_active' => false]);

        return back()->with('success', 'Prime writer deactivated successfully.');
    }
}
