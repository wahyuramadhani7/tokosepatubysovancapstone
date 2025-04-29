<x-app-layout>
      <x-slot name="header">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              {{ __('Sales Report') }}
          </h2>
      </x-slot>
  
      <div class="py-12">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
              <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                  <div class="p-6 text-gray-900">
                      <form method="GET" action="{{ route('transactions.report') }}" class="mb-6">
                          <div class="flex flex-wrap gap-4 items-end">
                              <div>
                                  <x-input-label for="date_start" :value="__('Start Date')" />
                                  <x-text-input id="date_start" class="block mt-1" type="date" name="date_start" :value="$dateStart" required />
                              </div>
                              <div>
                                  <x-input-label for="date_end" :value="__('End Date')" />
                                  <x-text-input id="date_end" class="block mt-1" type="date" name="date_end" :value="$dateEnd" required />
                              </div>
                              @if(Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                              <div>
                                  <x-input-label for="user_id" :value="__('Employee (Optional)')" />
                                  <select id="user_id" name="user_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1">
                                      <option value="">All Employees</option>
                                      @foreach(\App\Models\User::where('role', 'employee')->get() as $employee)
                                          <option value="{{ $employee->id }}" {{ request('user_id') == $employee->id ? 'selected' : '' }}>
                                              {{ $employee->name }}
                                          </option>
                                      @endforeach
                                  </select>
                              </div>
                              @endif
                              <div>
                                  <x-primary-button class="ml-3">
                                      {{ __('Filter Report') }}
                                  </x-primary-button>
                              </div>
                              <div>
                                  <button type="button" id="print-report" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                      {{ __('Print Report') }}
                                  </button>
                              </div>
                          </div>
                      </form>
  
                      <div class="mb-6">
                          <div class="bg-gray-50 p-4 rounded mb-4">
                              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                  <div>
                                      <h3 class="text-lg font-medium text-gray-900">Report Period</h3>
                                      <p class="text-gray-600">{{ \Carbon\Carbon::parse($dateStart)->format('d M Y') }} - {{ \Carbon\Carbon::parse($dateEnd)->format('d M Y') }}</p>
                                  </div>
                                  <div>
                                      <h3 class="text-lg font-medium text-gray-900">Total Transactions</h3>
                                      <p class="text-gray-600">{{ $totalTransactions }}</p>
                                  </div>
                                  <div>
                                      <h3 class="text-lg font-medium text-gray-900">Total Sales</h3>
                                      <p class="text-gray-600">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
                                  </div>
                              </div>
                          </div>
                      </div>
  
                      <div id="printable-report">
                          <div class="overflow-x-auto">
                              <table class="min-w-full divide-y divide-gray-200">
                                  <thead class="bg-gray-50">
                                      <tr>
                                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                              Date
                                          </th>
                                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                              Invoice
                                          </th>
                                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                              Customer
                                          </th>
                                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                              Payment
                                          </th>
                                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                              Cashier
                                          </th>
                                          <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                              Total
                                          </th>
                                      </tr>
                                  </thead>
                                  <tbody class="bg-white divide-y divide-gray-200">
                                      @forelse ($transactions as $transaction)
                                          <tr>
                                              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                  {{ $transaction->created_at->format('d M Y H:i') }}
                                              </td>
                                              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                  <a href="{{ route('transactions.show', $transaction) }}" class="text-indigo-600 hover:text-indigo-900">
                                                      {{ $transaction->invoice_number }}
                                                  </a>
                                              </td>
                                              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                  {{ $transaction->customer_name ?: 'Walk-in Customer' }}
                                              </td>
                                              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                  {{ ucfirst($transaction->payment_method) }}
                                              </td>
                                              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                  {{ $transaction->user->name }}
                                              </td>
                                              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                                  Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}
                                              </td>
                                          </tr>
                                      @empty
                                          <tr>
                                              <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                  No transactions found for the selected period.
                                              </td>
                                          </tr>
                                      @endforelse
                                  </tbody>
                                  <tfoot class="bg-gray-50">
                                      <tr>
                                          <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                                              Total Sales:
                                          </td>
                                          <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                                              Rp {{ number_format($totalSales, 0, ',', '.') }}
                                          </td>
                                      </tr>
                                  </tfoot>
                              </table>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  
      <style>
          @media print {
              body * {
                  visibility: hidden;
              }
              #printable-report, #printable-report * {
                  visibility: visible;
              }
              #printable-report {
                  position: absolute;
                  left: 0;
                  top: 0;
                  width: 100%;
              }
              .no-print {
                  display: none;
              }
          }
      </style>
  
      <script>
          document.getElementById('print-report').addEventListener('click', function() {
              window.print();
          });
      </script>
  </x-app-layout>