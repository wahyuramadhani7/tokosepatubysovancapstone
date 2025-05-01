<x-app-layout>
      <x-slot name="header">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              {{ __('Monitoring Pengunjung') }}
          </h2>
      </x-slot>
  
      <div class="py-12">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
              <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                  <div class="p-6">
                      <!-- Summary Cards -->
                      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                          <!-- Total Pengunjung Hari Ini -->
                          <div class="bg-blue-100 p-4 rounded-lg shadow">
                              <h3 class="text-lg font-semibold text-blue-800">Pengunjung Hari Ini</h3>
                              <p class="text-3xl font-bold text-blue-900">{{ $totalVisitorsToday }}</p>
                          </div>
                          
                          <!-- Pengunjung yang Sedang Ada -->
                          <div class="bg-green-100 p-4 rounded-lg shadow">
                              <h3 class="text-lg font-semibold text-green-800">Pengunjung Saat Ini</h3>
                              <p class="text-3xl font-bold text-green-900">{{ $currentVisitors }}</p>
                          </div>
                          
                          <!-- Total Pengunjung Bulan Ini -->
                          <div class="bg-purple-100 p-4 rounded-lg shadow">
                              <h3 class="text-lg font-semibold text-purple-800">Pengunjung Bulan Ini</h3>
                              <p class="text-3xl font-bold text-purple-900">{{ $totalVisitorsMonth }}</p>
                          </div>
                      </div>
                      
                      <!-- Chart -->
                      <div class="bg-white p-4 rounded-lg shadow mb-6">
                          <h3 class="text-lg font-semibold mb-4">Grafik Pengunjung Hari Ini</h3>
                          <canvas id="visitorChart" height="100"></canvas>
                      </div>
                      
                      <!-- Recent Visitors Table -->
                      <div class="bg-white p-4 rounded-lg shadow">
                          <h3 class="text-lg font-semibold mb-4">Pengunjung Terbaru</h3>
                          <div class="overflow-x-auto">
                              <table class="min-w-full bg-white">
                                  <thead class="bg-gray-100">
                                      <tr>
                                          <th class="py-2 px-4 border-b text-left">No.</th>
                                          <th class="py-2 px-4 border-b text-left">Waktu Masuk</th>
                                          <th class="py-2 px-4 border-b text-left">Status</th>
                                          <th class="py-2 px-4 border-b text-left">Waktu Keluar</th>
                                          <th class="py-2 px-4 border-b text-left">Gambar</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @forelse($recentVisitors as $index => $visitor)
                                          <tr>
                                              <td class="py-2 px-4 border-b">{{ $index + 1 }}</td>
                                              <td class="py-2 px-4 border-b">{{ $visitor->entry_time->format('H:i:s') }}</td>
                                              <td class="py-2 px-4 border-b">
                                                  @if($visitor->status == 'entered' && !$visitor->exit_time)
                                                      <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Masih di dalam</span>
                                                  @else
                                                      <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Sudah keluar</span>
                                                  @endif
                                              </td>
                                              <td class="py-2 px-4 border-b">{{ $visitor->exit_time ? $visitor->exit_time->format('H:i:s') : '-' }}</td>
                                              <td class="py-2 px-4 border-b">
                                                  @if($visitor->image_path)
                                                      <img src="{{ asset('storage/' . $visitor->image_path) }}" alt="Visitor Image" class="h-12 w-auto">
                                                  @else
                                                      <span class="text-gray-400">No image</span>
                                                  @endif
                                              </td>
                                          </tr>
                                      @empty
                                          <tr>
                                              <td colspan="5" class="py-4 px-4 border-b text-center text-gray-500">
                                                  Belum ada data pengunjung hari ini
                                              </td>
                                          </tr>
                                      @endforelse
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  
      @push('scripts')
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <script>
          document.addEventListener('DOMContentLoaded', function() {
              const ctx = document.getElementById('visitorChart').getContext('2d');
              const visitorChart = new Chart(ctx, {
                  type: 'line',
                  data: {
                      labels: @json($labels),
                      datasets: [{
                          label: 'Jumlah Pengunjung',
                          data: @json($hourlyData),
                          backgroundColor: 'rgba(54, 162, 235, 0.2)',
                          borderColor: 'rgba(54, 162, 235, 1)',
                          borderWidth: 2,
                          tension: 0.2,
                          pointRadius: 4,
                          fill: true
                      }]
                  },
                  options: {
                      responsive: true,
                      scales: {
                          y: {
                              beginAtZero: true,
                              ticks: {
                                  precision: 0
                              }
                          }
                      },
                      plugins: {
                          legend: {
                              display: true,
                              position: 'top'
                          },
                          tooltip: {
                              mode: 'index',
                              intersect: false,
                          }
                      }
                  }
              });
          });
      </script>
      @endpush
  </x-app-layout>