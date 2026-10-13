<?php

namespace App\Http\Controllers;

use App\Models\Spreadsheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SpreadsheetController extends Controller
{
    /**
     * Display the main spreadsheets dashboard/workspace.
     */
    public function index(Request $request)
    {
        $activeId = $request->query('id');

        // Check if there are any spreadsheets, if none create an initial one
        $count = Spreadsheet::count();
        if ($count === 0) {
            $defaultSheet = Spreadsheet::create([
                'title' => 'My First Spreadsheet',
                'sheet_data' => json_encode($this->getDefaultSheetData('My First Spreadsheet')),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
            $activeId = $defaultSheet->id;
        }

        $spreadsheets = Spreadsheet::with(['creator', 'updater'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $activeSpreadsheet = null;
        if ($activeId) {
            $activeSpreadsheet = Spreadsheet::with(['creator', 'updater'])->find($activeId);
        }
        if (!$activeSpreadsheet && $spreadsheets->isNotEmpty()) {
            $activeSpreadsheet = $spreadsheets->first();
        }

        return view('back-end.spreadsheets.index', compact('spreadsheets', 'activeSpreadsheet'));
    }

    /**
     * Return JSON list of spreadsheets for live search & sidebar refresh.
     */
    public function list(Request $request)
    {
        $query = Spreadsheet::with(['creator:id,name', 'updater:id,name']);

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        $filter = $request->input('filter', 'all');
        if ($filter === 'favorite') {
            $query->where('is_favorite', true);
        } elseif ($filter === 'mine' && Auth::check()) {
            $query->where('created_by', Auth::id());
        }

        $spreadsheets = $query->orderBy('updated_at', 'desc')->get()->map(function ($sheet) {
            return [
                'id' => $sheet->id,
                'uuid' => $sheet->uuid,
                'title' => $sheet->title,
                'is_favorite' => (bool) $sheet->is_favorite,
                'creator_name' => $sheet->creator?->name ?? 'System',
                'updater_name' => $sheet->updater?->name ?? ($sheet->creator?->name ?? 'System'),
                'updated_at_human' => $sheet->updated_at?->diffForHumans() ?? 'Just now',
                'updated_at_formatted' => $sheet->updated_at?->format('d M Y, h:i A') ?? '',
            ];
        });

        return response()->json([
            'success' => true,
            'spreadsheets' => $spreadsheets,
        ]);
    }

    /**
     * Create a new blank spreadsheet.
     */
    public function store(Request $request)
    {
        $title = trim($request->input('title', '')) ?: 'Untitled Spreadsheet';
        $sheetData = $request->input('sheet_data');

        if (empty($sheetData)) {
            $sheetData = json_encode($this->getDefaultSheetData($title));
        } elseif (is_array($sheetData)) {
            $sheetData = json_encode($sheetData);
        }

        $spreadsheet = Spreadsheet::create([
            'title' => $title,
            'sheet_data' => $sheetData,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Spreadsheet created successfully',
            'spreadsheet' => [
                'id' => $spreadsheet->id,
                'uuid' => $spreadsheet->uuid,
                'title' => $spreadsheet->title,
                'sheet_data' => $spreadsheet->sheet_data,
                'is_favorite' => false,
                'creator_name' => Auth::user()?->name ?? 'You',
                'updater_name' => Auth::user()?->name ?? 'You',
                'updated_at_human' => 'Just now',
            ],
        ]);
    }

    /**
     * Get sheet data for a specific spreadsheet.
     */
    public function show($id)
    {
        $spreadsheet = Spreadsheet::with(['creator:id,name', 'updater:id,name'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'spreadsheet' => [
                'id' => $spreadsheet->id,
                'uuid' => $spreadsheet->uuid,
                'title' => $spreadsheet->title,
                'sheet_data' => $spreadsheet->sheet_data,
                'is_favorite' => (bool) $spreadsheet->is_favorite,
                'creator_name' => $spreadsheet->creator?->name ?? 'System',
                'updater_name' => $spreadsheet->updater?->name ?? 'System',
                'updated_at_human' => $spreadsheet->updated_at?->diffForHumans() ?? 'Just now',
                'updated_at_formatted' => $spreadsheet->updated_at?->format('d M Y, h:i A') ?? '',
            ],
        ]);
    }

    /**
     * Save sheet data (Auto-save / Manual save).
     */
    public function save(Request $request, $id)
    {
        $spreadsheet = Spreadsheet::findOrFail($id);

        $sheetData = $request->input('sheet_data');
        if (is_array($sheetData)) {
            $sheetData = json_encode($sheetData);
        }

        if ($sheetData !== null) {
            $spreadsheet->sheet_data = $sheetData;
        }

        if ($request->has('title')) {
            $title = trim($request->input('title'));
            if (!empty($title)) {
                $spreadsheet->title = $title;
            }
        }

        $spreadsheet->updated_by = Auth::id();
        $spreadsheet->save();

        return response()->json([
            'success' => true,
            'message' => 'Saved successfully',
            'updated_at_human' => $spreadsheet->updated_at->diffForHumans(),
            'updated_at_formatted' => $spreadsheet->updated_at->format('d M Y, h:i A'),
            'updater_name' => Auth::user()?->name ?? 'You',
        ]);
    }

    /**
     * Rename a spreadsheet.
     */
    public function rename(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $spreadsheet = Spreadsheet::findOrFail($id);
        $spreadsheet->title = trim($request->input('title'));
        $spreadsheet->updated_by = Auth::id();
        $spreadsheet->save();

        return response()->json([
            'success' => true,
            'title' => $spreadsheet->title,
            'message' => 'Renamed successfully',
        ]);
    }

    /**
     * Duplicate an existing spreadsheet.
     */
    public function duplicate($id)
    {
        $original = Spreadsheet::findOrFail($id);

        $copyTitle = $original->title . ' (Copy)';
        $newSheet = Spreadsheet::create([
            'title' => $copyTitle,
            'sheet_data' => $original->sheet_data,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'is_favorite' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Spreadsheet duplicated successfully',
            'spreadsheet' => [
                'id' => $newSheet->id,
                'uuid' => $newSheet->uuid,
                'title' => $newSheet->title,
                'is_favorite' => false,
                'creator_name' => Auth::user()?->name ?? 'You',
                'updater_name' => Auth::user()?->name ?? 'You',
                'updated_at_human' => 'Just now',
            ],
        ]);
    }

    /**
     * Toggle favorite status.
     */
    public function toggleFavorite($id)
    {
        $spreadsheet = Spreadsheet::findOrFail($id);
        $spreadsheet->is_favorite = !$spreadsheet->is_favorite;
        $spreadsheet->save();

        return response()->json([
            'success' => true,
            'is_favorite' => (bool) $spreadsheet->is_favorite,
            'message' => $spreadsheet->is_favorite ? 'Added to favorites' : 'Removed from favorites',
        ]);
    }

    /**
     * Delete (soft-delete) spreadsheet.
     */
    public function destroy($id)
    {
        $spreadsheet = Spreadsheet::findOrFail($id);
        $spreadsheet->delete();

        return response()->json([
            'success' => true,
            'message' => 'Spreadsheet moved to trash',
        ]);
    }

    /**
     * Helper to get default Luckysheet empty workbook structure.
     */
    private function getDefaultSheetData(string $sheetName = 'Sheet1'): array
    {
        return [
            [
                'name' => 'Sheet1',
                'color' => '',
                'status' => 1,
                'order' => 0,
                'data' => [],
                'config' => [],
                'index' => 0,
                'row' => 84,
                'column' => 26,
            ],
        ];
    }
}
