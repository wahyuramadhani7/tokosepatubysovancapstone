<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorMonitoringController extends Controller
{
    public function index()
    {
        // Data untuk summaries
        $today = now()->format('Y-m-d');
        $totalVisitorsToday = Visitor::whereDate('entry_time', $today)->count();
        $currentVisitors = Visitor::where('status', 'entered')
                             ->whereNull('exit_time')
                             ->count();
        $totalVisitorsMonth = Visitor::whereMonth('entry_time', now()->month)
                                ->whereYear('entry_time', now()->year)
                                ->count();

        // Data pengunjung per jam untuk hari ini
        $hourlyVisitors = Visitor::select(
                DB::raw('HOUR(entry_time) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->whereDate('entry_time', $today)
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->hour => $item->count];
            });

        // Format untuk chart
        $hourlyData = [];
        $labels = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $labels[] = sprintf('%02d:00', $hour);
            $hourlyData[] = $hourlyVisitors->get($hour, 0);
        }

        // Recent visitors
        $recentVisitors = Visitor::whereDate('entry_time', $today)
                            ->latest('entry_time')
                            ->take(10)
                            ->get();

        return view('visitor-monitoring.index', compact(
            'totalVisitorsToday',
            'currentVisitors',
            'totalVisitorsMonth',
            'hourlyData',
            'labels',
            'recentVisitors'
        ));
    }

    // Endpoint untuk menerima data dari ESP Cam
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
}