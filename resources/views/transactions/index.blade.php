<x-app-layout>
      <x-slot name="header">
          <div class="flex justify-between">
              <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                  {{ __('Transactions') }}
              </h2>
              <a href="{{ route('transactions.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                  {{ __('New Transaction') }}
              </a>
          </div>
      </x-slot>
  
      <div class="py-12">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
              <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                  <div class="p-6 text-gray-900">
  
                      @if (session('success'))
                          <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                              <p>{{ session('success') }}</p>
                          </div>
                      @endif
  
                      <!-- Filters and search -->
                      <div class="mb-4 bg-gray-50 p-4 rounded">
                          <form action="{{ route('transactions.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">
                              <div>
                                  <x-input-label for="search" :value="__('Search')" />
                                  <x-text-input id="search" name="search" :value="request('search')" class="w-full md:w-64" placeholder="Invoice or customer..." />
                              </div>
                              <div>
                                  <x-input-label for="date_start" :value="__('Date From')" />
                                  <x-text-input id="date_start" name="date_start" type="date" :value="request('date_start')" class="w-full md:w-auto" />
                              </div>
                              <div>
                                  <x-input-label for="date_end" :value="__('Date To')" />
                                  <x-text-input id="date_end" name="date_end" type="date" :value="request('date_end')" class="w-full md:w-auto" />
                              </div>
                              <div>
                                  <x-primary-button>
                                      {{ __('Filter') }}
                                  </x-primary-button>
                                  <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 ml-2">
                                      {{ __('Reset') }}
                                  </a>
                              </div>
                          </form>
                      </div>
  
                      <!-- Transaction Summary -->
                      <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                          <div class="bg-blue-50 p-4 rounded shadow">
                              <h4 class="text-blue-700 text-sm font-medium">Total Transactions</h4>
                              <p class="text-2xl font-bold">{{ $transactions->total() }}</p>
                          </div>
                          <div class="bg-green-50 p-4 rounded shadow">
                              <h4 class="text-green-700 text-sm font-medium">Total Sales</h4>
                              <p class="text-2xl font-bold">Rp {{ number_format($transactions->sum('final_amount'), 0, ',', '.') }}</p>
                          </div>
                          <div class="bg-purple-50 p-4 rounded shadow">
                              <h4 class="text-purple-700 text-sm font-medium">Average Sale</h4>
                              <p class="text-2xl font-bold">Rp {{ number_format($transactions->count() ? $transactions->sum('final_amount') / $transactions->count() : 0, 0, ',', '.') }}</p>
                          </div>
                      </div>
  
                      <div class="overflow-x-auto">
                          <table class="min-w-full divide-y divide-gray-200">
                              <thead class="bg-gray-50">
                                  <tr>
                                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                          Invoice
                                      </th>
                                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                          Date
                                      </th>
                                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                          Customer
                                      </th>
                                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                          Items
                                      </th>
                                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                          Total
                                      </th>
                                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                          Payment
                                      </th>
                                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                          Cashier
                                      </th>
                                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                          Actions
                                      </th>
                                  </tr>
                              </thead>
                              <tbody class="bg-white divide-y divide-gray-200">
                                  @forelse ($transactions as $transaction)
                                      <tr>
                                          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                              {{ $transaction->invoice_number }}
                                          </td>
                                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                              {{ $transaction->created_at->format('d M Y H:i') }}
                                          </td>
                                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                              {{ $transaction->customer_name ?: 'Walk-in Customer' }}
                                          </td>
                                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                              {{ $transaction->items->sum('quantity') }} items
                                          </td>
                                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                              Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}
                                          </td>
                                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                  {{ $transaction->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                  {{ ucfirst($transaction->payment_method) }} / {{ ucfirst($transaction->payment_status) }}
                                              </span>
                                          </td>
                                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                              {{ $transaction->user->name }}
                                          </td>
                                          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                              <a href="{{ route('transactions.show', $transaction) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                              <a href="{{ route('transactions.print', $transaction) }}" class="text-green-600 hover:text-green-900" target="_blank">Print</a>
                                          </td>
                                      </tr>
                                  @empty
                                      <tr>
                                          <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                              No transactions found.
                                          </td>
                                      </tr>
                                  @endforelse
                              </tbody>
                          </table>
                      </div>
  
                      <div class="mt-4">
                          {{ $transactions->links() }}
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </x-app-layout>