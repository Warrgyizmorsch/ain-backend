<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WriterApiController extends Controller
{
    /**
     * Display a listing of active writers (flag = 0).
     */
    public function index(Request $request)
    {
        $query = Writer::where('flag', 0);

        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where('writer_name', 'like', '%' . $search . '%');
        }

        // If explicitly paginated or limit passed, use pagination. Otherwise, return all.
        if ($request->has('page') || $request->has('limit')) {
            $limit = intval($request->query('limit', 15));
            $limit = max(1, min(100, $limit));
            $writers = $query->paginate($limit);
        } else {
            $writers = $query->get();
        }

        return response()->json([
            'success' => true,
            'data' => $writers
        ]);
    }

    /**
     * Store a newly created writer.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'writer_name' => 'required|string|max:255',
            'writer_number' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $writer = new Writer();
        $writer->writer_name = $request->input('writer_name');
        $writer->writer_number = $request->input('writer_number') ?? '';
        $writer->flag = 0;
        $writer->created_at = now()->format('Y-m-d H:i:s');
        $writer->updated_at = now()->format('Y-m-d H:i:s');
        $writer->save();

        return response()->json([
            'success' => true,
            'message' => 'Writer created successfully',
            'data' => $writer
        ], 201);
    }

    /**
     * Display the specified writer details.
     */
    public function show(Request $request, $id = null)
    {
        $id = $id ?? $request->query('id');

        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'Writer ID is required'
            ], 400);
        }

        $writer = Writer::where('flag', 0)->find($id);

        if (!$writer) {
            return response()->json([
                'success' => false,
                'message' => 'Writer not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $writer
        ]);
    }

    /**
     * Update the specified writer.
     */
    public function update(Request $request, $id)
    {
        $writer = Writer::where('flag', 0)->find($id);

        if (!$writer) {
            return response()->json([
                'success' => false,
                'message' => 'Writer not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'writer_name' => 'sometimes|required|string|max:255',
            'writer_number' => 'nullable|string|max:255',
            'flag' => 'nullable|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->has('writer_name')) {
            $writer->writer_name = $request->input('writer_name');
        }
        if ($request->has('writer_number')) {
            $writer->writer_number = $request->input('writer_number') ?? '';
        }
        if ($request->has('flag')) {
            $writer->flag = $request->input('flag');
        }

        $writer->updated_at = now()->format('Y-m-d H:i:s');
        $writer->save();

        return response()->json([
            'success' => true,
            'message' => 'Writer updated successfully',
            'data' => $writer
        ]);
    }

    /**
     * Deactivate / Soft Delete the specified writer (sets flag = 1).
     */
    public function destroy($id)
    {
        $writer = Writer::where('flag', 0)->find($id);

        if (!$writer) {
            return response()->json([
                'success' => false,
                'message' => 'Writer not found'
            ], 404);
        }

        $writer->flag = 1;
        $writer->updated_at = now()->format('Y-m-d H:i:s');
        $writer->save();

        return response()->json([
            'success' => true,
            'message' => 'Writer deleted successfully'
        ]);
    }
}
