<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VisitorController extends Controller
{
    public function updateStatus(Request $request)
    {
        $request->validate([
            'sensor_a' => 'boolean',
            'sensor_b' => 'boolean',
            'visitor_count' => 'integer',
            'event' => 'nullable|string|in:entry,exit',
        ]);

        if ($request->event === 'entry') {
            Visitor::create([
                'entry_time' => now(),
                'status' => 'entered',
            ]);
        } elseif ($request->event === 'exit') {
            $visitor = Visitor::where('status', 'entered')->latest()->first();
            if ($visitor) {
                $visitor->update([
                    'exit_time' => now(),
                    'status' => 'exited',
                ]);
            }
        }

        return response()->json(['message' => 'Data updated successfully']);
    }

    public function getDashboardData()
    {
        $today = Carbon::today();
        $month = Carbon::now()->startOfMonth();

        $totalVisitorsToday = Visitor::whereDate('entry_time', $today)->count();
        $currentVisitors = Visitor::where('status', 'entered')->count();
        $totalVisitorsMonth = Visitor::where('entry_time', '>=', $month)->count();

        $hourlyData = Visitor::whereDate('entry_time', $today)
            ->selectRaw('HOUR(entry_time) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->pluck('count', 'hour')
            ->toArray();

        $labels = [];
        $data = [];
        for ($i = 0; $i < 24; $i++) {
            $labels[] = sprintf('%02d:00', $i);
            $data[] = $hourlyData[$i] ?? 0;
        }

        $recentVisitors = Visitor::latest()->take(10)->get();

        return response()->json([
            'totalVisitorsToday' => $totalVisitorsToday,
            'currentVisitors' => $currentVisitors,
            'totalVisitorsMonth' => $totalVisitorsMonth,
            'labels' => $labels,
            'hourlyData' => $data,
            'recentVisitors' => $recentVisitors,
        ]);
    }
}