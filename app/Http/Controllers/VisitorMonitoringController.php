<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VisitorMonitoringController extends Controller
{
    /**
     * Menampilkan dashboard monitoring pengunjung
     */
    public function index()
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error fetching dashboard data: ' . $e->getMessage());
            return view('visitor-monitoring.index', [
                'error' => 'Gagal memuat data dashboard. Silakan coba lagi.',
                'totalVisitorsToday' => 0,
                'currentVisitors' => 0,
                'totalVisitorsMonth' => 0,
                'hourlyData' => [],
                'labels' => [],
                'recentVisitors' => []
            ]);
        }
    }

    /**
     * Endpoint untuk mencatat pengunjung masuk dari ESP
     */
    public function storeVisitorEntry(Request $request)
    {
        try {
            // Validasi API Key
            if ($request->header('X-API-Key') !== env('ESP_API_KEY', 'your-secret-key')) {
                Log::error('Unauthorized ESP request to /visitor-entry from IP: ' . $request->ip());
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Validasi request
            $request->validate([
                'image' => 'nullable|image|max:2048', // Gambar opsional
            ]);

            // Simpan gambar jika ada
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('visitor-images', 'public');
                Log::info('Image uploaded for visitor entry: ' . $imagePath);
            }

            // Buat record visitor baru
            $visitor = Visitor::create([
                'entry_time' => now(),
                'image_path' => $imagePath,
                'status' => 'entered'
            ]);

            Log::info('Visitor entered: ID ' . $visitor->id . ' from IP: ' . $request->ip());

            return response()->json([
                'success' => true,
                'visitor_id' => $visitor->id,
                'message' => 'Visitor entry recorded successfully'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error recording visitor entry: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to record visitor entry'
            ], 500);
        }
    }

    /**
     * Endpoint untuk mencatat pengunjung keluar dari ESP
     */
    public function storeVisitorExit(Request $request, $id)
    {
        try {
            // Validasi API Key
            if ($request->header('X-API-Key') !== env('ESP_API_KEY', 'your-secret-key')) {
                Log::error('Unauthorized ESP request to /visitor-exit/' . $id . ' from IP: ' . $request->ip());
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Cari visitor berdasarkan ID
            $visitor = Visitor::find($id);
            if (!$visitor) {
                Log::warning('Visitor not found for exit: ID ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Visitor not found'
                ], 404);
            }

            // Update status dan exit_time
            $visitor->update([
                'exit_time' => now(),
                'status' => 'exited'
            ]);

            Log::info('Visitor exited: ID ' . $id . ' from IP: ' . $request->ip());

            return response()->json([
                'success' => true,
                'message' => 'Visitor exit recorded successfully'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error recording visitor exit: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to record visitor exit'
            ], 500);
        }
    }
}