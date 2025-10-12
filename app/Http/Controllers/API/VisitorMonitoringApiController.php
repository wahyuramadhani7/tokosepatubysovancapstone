<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorMonitoringApiController extends Controller
{
    /**
     * Display a listing of visitors with optional date filtering
     */
    public function index(Request $request)
    {
        $query = Visitor::query();

        // Filter by date if provided
        if ($request->has('date')) {
            $query->whereDate('entry_time', $request->date);
        }

        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Pagination
        $perPage = $request->input('per_page', 10);
        $visitors = $query->latest('entry_time')->paginate($perPage);

        // Get statistics
        $today = now()->format('Y-m-d');
        $stats = [
            'total_visitors_today' => Visitor::whereDate('entry_time', $today)->count(),
            'current_visitors' => Visitor::where('status', 'entered')
                                    ->whereNull('exit_time')
                                    ->count(),
            'total_visitors_month' => Visitor::whereMonth('entry_time', now()->month)
                                        ->whereYear('entry_time', now()->year)
                                        ->count(),
            'hourly_visitors' => Visitor::select(
                    DB::raw('HOUR(entry_time) as hour'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereDate('entry_time', $today)
                ->groupBy('hour')
                ->orderBy('hour')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->hour => $item->count];
                })
                ->toArray()
        ];

        return response()->json([
            'success' => true,
            'data' => $visitors->items(),
            'pagination' => [
                'total' => $visitors->total(),
                'per_page' => $visitors->perPage(),
                'current_page' => $visitors->currentPage(),
                'last_page' => $visitors->lastPage()
            ],
            'statistics' => $stats,
            'message' => 'Visitors retrieved successfully'
        ]);
    }

    /**
     * Display a specific visitor
     */
    public function show($id)
    {
        $visitor = Visitor::find($id);

        if (!$visitor) {
            return response()->json([
                'success' => false,
                'message' => 'Visitor not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $visitor,
            'message' => 'Visitor retrieved successfully'
        ]);
    }

    /**
     * Store a new visitor entry
     */
    public function storeVisitorEntry(Request $request)
    {
        // Validasi request
        $request->validate([
            'image' => 'nullable|image|max:2048', // Optional image upload
        ]);

        $imagePath = null;

        // Simpan gambar jika ada
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('visitor-images', 'public');
        }

        // Buat record visitor baru
        $visitor = Visitor::create([
            'entry_time' => now(),
            'image_path' => $imagePath,
            'status' => 'entered'
        ]);

        return response()->json([
            'success' => true,
            'visitor_id' => $visitor->id,
            'message' => 'Visitor entry recorded successfully'
        ]);
    }

    /**
     * Record visitor exit
     */
    public function storeVisitorExit(Request $request, $id)
    {
        $visitor = Visitor::findOrFail($id);
        
        $visitor->update([
            'exit_time' => now(),
            'status' => 'exited'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Visitor exit recorded successfully'
        ]);
    }

    /**
     * Get visitor statistics
     */
    public function statistics()
    {
        $today = now()->format('Y-m-d');

        $stats = [
            'total_visitors_today' => Visitor::whereDate('entry_time', $today)->count(),
            'current_visitors' => Visitor::where('status', 'entered')
                                    ->whereNull('exit_time')
                                    ->count(),
            'total_visitors_month' => Visitor::whereMonth('entry_time', now()->month)
                                        ->whereYear('entry_time', now()->year)
                                        ->count(),
            'hourly_visitors' => Visitor::select(
                    DB::raw('HOUR(entry_time) as hour'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereDate('entry_time', $today)
                ->groupBy('hour')
                ->orderBy('hour')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->hour => $item->count];
                })
                ->toArray()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Statistics retrieved successfully'
        ]);
    }
}