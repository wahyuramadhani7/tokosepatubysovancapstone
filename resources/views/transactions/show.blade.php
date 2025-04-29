<x-app-layout>
      <x-slot name="header">
          <div class="flex justify-between">
              <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                  {{ __('Transaction Details') }} - {{ $transaction->invoice_number }}
              </h2>
              <div>
                  <a href="{{ route('transactions.print', $transaction) }}" target="_blank" class="ml-4 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                      {{ __('Print Receipt') }}
                  </a>
                  <a href="{{ route('transactions.index') }}" class="ml-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                      {{ __('Back to Transactions') }}
                  </a>
              </div>
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
  
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                          <!-- Transaction Information -->
                          <div>
                              <h3 class="text-lg font-medium mb-4">Transaction Information</h3>
                              <div class="bg-gray-50 rounded p-4">
                                  <div class="grid grid-cols-2 gap-4">
                                      <div>
                                          <p class="text-sm text-gray-600">Invoice Number</p>
                                          <p class="font-medium">{{ $transaction->invoice_number }}</p>
                                      </div>
                                      <div>
                                          <p class="text-sm text-gray-600">Date</p>
                                          <p class="font-medium">{{ $transaction->created_at->format('d M Y H:i') }}</p>
                                      </div>
                                      <div>
                                          <p class="text-sm text-gray-600">Payment Method</p>
                                          <p class="font-medium">{{ ucfirst($transaction->payment_method) }}</p>
                                      </div>
                                      <div>
                                          <p class="text-sm text-gray-600">Payment Status</p>
                                          <p class="font-medium">{{ ucfirst($transaction->payment_status) }}</p>
                                      </div>
                                      <div>
                                          <p class="text-sm text-gray-600">Cashier</p>
                                          <p class="font-medium">{{ $transaction->user->name }}</p>
                                      </div>
                                  </div>
                              </div>
  
                              <!-- Customer Information -->
                              <h3 class="text-lg font-medium mt-6 mb-4">Customer Information</h3>
                              <div class="bg-gray-50 rounded p-4">
                                  <div class="mb-3">
                                      <p class="text-sm text-gray-600">Name</p>
                                      <p class="font-medium">{{ $transaction->customer_name ?: 'Walk-in Customer' }}</p>
                                  </div>
                                  @if($transaction->customer_phone)
                                  <div class="mb-3">
                                      <p class="text-sm text-gray-600">Phone</p>
                                      <p class="font-medium">{{ $transaction->customer_phone }}</p>
                                  </div>
                                  @endif
                                  @if($transaction->customer_email)
                                  <div class="mb-3">
                                      <p class="text-sm text-gray-600">Email</p>
                                      <p class="font-medium">{{ $transaction->customer_email }}</p>
                                  </div>
                                  @endif
                                  @if($transaction->notes)
                                  <div>
                                      <p class="text-sm text-gray-600">Notes</p>
                                      <p class="font-medium">{{ $transaction->notes }}</p>
                                  </div>
                                  @endif
                              </div>
                          </div>
  
                          <!-- Items & Summary -->
                          <div>
                              <h3 class="text-lg font-medium mb-4">Items</h3>
                              <div class="bg-gray-50 rounded p-4">
                                  <table class="min-w-full divide-y divide-gray-200">
                                      <thead>
                                          <tr>
                                              <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                              <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                              <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                              <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                          </tr>
                                      </thead>
                                      <tbody class="divide-y divide-gray-200">
                                          @foreach ($transaction->items as $item)
                                              <tr>
                                                  <td class="px-3 py-3 text-sm">{{ $item->product->name }}</td>
                                                  <td class="px-3 py-3 text-sm text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                                  <td class="px-3 py-3 text-sm text-right">{{ $item->quantity }}</td>
                                                  <td class="px-3 py-3 text-sm text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                              </tr>
                                          @endforeach
                                      </tbody>
                                  </table>
                              </div>
  
                              <!-- Summary -->
                              <div class="mt-6 bg-gray-50 rounded p-4">
                                  <div class="flex justify-between mb-2">
                                      <span>Subtotal:</span>
                                      <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                                  </div>
                                  @if($transaction->discount_amount > 0)
                                  <div class="flex justify-between mb-2">
                                      <span>Discount:</span>
                                      <span>Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                                  </div>
                                  @endif
                                  <div class="flex justify-between mb-2">
                                      <span>Tax (11%):</span>
                                      <span>Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</span>
                                  </div>
                                  <div class="flex justify-between font-bold text-lg">
                                      <span>Total:</span>
                                      <span>Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}</span>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </x-app-layout>