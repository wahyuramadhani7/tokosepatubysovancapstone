<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<xaiArtifact artifact_id="c9052f56-317a-4171-9e3b-9d9ee38f5c8e" artifact_version_id="8e8c19c1-f58e-4f5c-98d4-2fcb16c1dc49" title="index.blade.php" contentType="text/html">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Monitoring Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Visitor Monitoring Dashboard</h1>
            <a href="{{ auth()->user()->role === 'owner' ? route('owner.dashboard') : route('employee.dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Back to Dashboard
            </a>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Visitors Today</h2>
                <p class="text-3xl font-bold text-blue-600">{{ $totalVisitorsToday }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Current Visitors</h2>
                <p class="text-3xl font-bold text-green-600">{{ $currentVisitors }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Visitors This Month</h2>
                <p class="text-3xl font-bold text-purple-600">{{ $totalVisitorsMonth }}</p>
            </div>
        </div>

        <!-- Hourly Visitors Chart -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Hourly Visitors Today</h2>
            <canvas id="hourlyChart" height="100"></canvas>
        </div>

        <!-- Recent Visitors Table -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Recent Visitors</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">ID</th>
                            <th class="py-3 px-6 text-left">Entry Time</th>
                            <th class="py-3 px-6 text-left">Status</th>
                            <th class="py-3 px-6 text-left">Image</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm">
                        @foreach ($recentVisitors as $visitor)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6">{{ $visitor->id }}</td>
                                <td class="py-3 px-6">{{ $visitor->entry_time->format('Y-m-d H:i:s') }}</td>
                                <td class="py-3 px-6">
                                    <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold {{ $visitor->status === 'entered' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($visitor->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-6">
                                    @if ($visitor->image_path)
                                        <img src="{{ asset('storage/' . $visitor->image_path) }}" alt="Visitor Image" class="w-12 h-12 object-cover rounded">
                                    @else
                                        No Image
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script>
        const ctx = document.getElementById('hourlyChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Visitors per Hour',
                    data: @json($hourlyData),
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Visitors'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Hour of Day'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    </script>
</body>
</html>