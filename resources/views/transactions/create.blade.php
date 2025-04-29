<x-app-layout>
      <x-slot name="header">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              {{ __('New Transaction') }}
          </h2>
      </x-slot>
  
      <div class="py-12">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
              <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                  <div class="p-6 text-gray-900">
                      <form id="transaction-form" method="POST" action="{{ route('transactions.store') }}">
                          @csrf
  
                          @if ($errors->any())
                              <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                                  <ul>
                                      @foreach ($errors->all() as $error)
                                          <li>{{ $error }}</li>
                                      @endforeach
                                  </ul>
                              </div>
                          @endif
  
                          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                              <div>
                                  <h3 class="text-lg font-medium mb-4">Customer Information</h3>
                                  <div class="mb-4">
                                      <x-input-label for="customer_name" :value="__('Customer Name')" />
                                      <x-text-input id="customer_name" class="block mt-1 w-full" type="text" name="customer_name" :value="old('customer_name')" />
                                  </div>
                                  <div class="mb-4">
                                      <x-input-label for="customer_phone" :value="__('Customer Phone')" />
                                      <x-text-input id="customer_phone" class="block mt-1 w-full" type="text" name="customer_phone" :value="old('customer_phone')" />
                                  </div>
                                  <div class="mb-4">
                                      <x-input-label for="customer_email" :value="__('Customer Email')" />
                                      <x-text-input id="customer_email" class="block mt-1 w-full" type="email" name="customer_email" :value="old('customer_email')" />
                                  </div>
                                  <div class="mb-4">
                                      <x-input-label for="payment_method" :value="__('Payment Method')" />
                                      <select id="payment_method" name="payment_method" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                          <option value="cash">Cash</option>
                                          <option value="credit_card">Credit Card</option>
                                          <option value="transfer">Bank Transfer</option>
                                      </select>
                                  </div>
                                  <div class="mb-4">
                                      <x-input-label for="notes" :value="__('Notes')" />
                                      <textarea id="notes" name="notes" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="3">{{ old('notes') }}</textarea>
                                  </div>
                              </div>
  
                              <div>
                                  <div class="flex justify-between mb-4">
                                      <h3 class="text-lg font-medium">Products</h3>
                                      <button type="button" id="add-product" class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                          Add Product
                                      </button>
                                  </div>
  
                                  <div id="products-container">
                                      <div class="product-row bg-gray-50 p-4 rounded mb-4">
                                          <div class="mb-3">
                                              <label class="block text-sm font-medium text-gray-700">Product</label>
                                              <select name="products[0][id]" class="product-select border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                                  <option value="">Select Product</option>
                                                  @foreach ($products as $product)
                                                      <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}" data-stock="{{ $product->stock }}">
                                                          {{ $product->name }} - Rp {{ number_format($product->selling_price, 0, ',', '.') }} (Stock: {{ $product->stock }})
                                                      </option>
                                                  @endforeach
                                              </select>
                                          </div>
                                          <div class="grid grid-cols-2 gap-3">
                                              <div>
                                                  <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                                  <input type="number" name="products[0][quantity]" class="quantity-input border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" min="1" max="1" value="1" required>
                                              </div>
                                              <div>
                                                  <label class="block text-sm font-medium text-gray-700">Price</label>
                                                  <input type="text" class="price-display border-gray-300 bg-gray-100 rounded-md shadow-sm block mt-1 w-full" value="Rp 0" readonly>
                                              </div>
                                          </div>
                                          <div class="mt-3 text-right">
                                              <span class="subtotal font-medium">Subtotal: Rp 0</span>
                                          </div>
                                      </div>
                                  </div>
  
                                  <div class="mt-4 p-4 bg-gray-50 rounded">
                                      <div class="flex justify-between mb-2">
                                          <span>Subtotal:</span>
                                          <span id="total-amount">Rp 0</span>
                                      </div>
                                      <div class="flex justify-between mb-2">
                                          <span>Discount:</span>
                                          <div class="flex items-center">
                                              <span class="mr-2">Rp</span>
                                              <input type="number" name="discount_amount" id="discount-amount" class="w-24 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="0" min="0">
                                          </div>
                                      </div>
                                      <div class="flex justify-between mb-2">
                                          <span>Tax (11%):</span>
                                          <span id="tax-amount">Rp 0</span>
                                      </div>
                                      <div class="flex justify-between font-bold">
                                          <span>Total:</span>
                                          <span id="final-amount">Rp 0</span>
                                      </div>
                                  </div>
                              </div>
                          </div>
  
                          <div class="flex justify-end mt-6">
                              <x-secondary-button type="button" onclick="window.history.back()" class="mr-3">
                                  {{ __('Cancel') }}
                              </x-secondary-button>
                              <x-primary-button>
                                  {{ __('Complete Transaction') }}
                              </x-primary-button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>
  
      <script>
          document.addEventListener('DOMContentLoaded', function() {
              let productCounter = 1;
              const productsContainer = document.getElementById('products-container');
              const addProductBtn = document.getElementById('add-product');
              const totalAmountEl = document.getElementById('total-amount');
              const taxAmountEl = document.getElementById('tax-amount');
              const finalAmountEl = document.getElementById('final-amount');
              const discountInput = document.getElementById('discount-amount');
              
              // Init calculations
              calculateTotals();
              
              // Add product button event
              addProductBtn.addEventListener('click', function() {
                  const newRow = document.createElement('div');
                  newRow.className = 'product-row bg-gray-50 p-4 rounded mb-4';
                  newRow.innerHTML = `
                      <div class="flex justify-between mb-3">
                          <label class="block text-sm font-medium text-gray-700">Product</label>
                          <button type="button" class="remove-product text-red-600 text-sm">Remove</button>
                      </div>
                      <div class="mb-3">
                          <select name="products[${productCounter}][id]" class="product-select border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                              <option value="">Select Product</option>
                              ${Array.from(document.querySelectorAll('#products-container .product-select:first-child option')).map(opt => 
                                  `<option value="${opt.value}" data-price="${opt.dataset.price}" data-stock="${opt.dataset.stock}">${opt.textContent}</option>`
                              ).join('')}
                          </select>
                      </div>
                      <div class="grid grid-cols-2 gap-3">
                          <div>
                              <label class="block text-sm font-medium text-gray-700">Quantity</label>
                              <input type="number" name="products[${productCounter}][quantity]" class="quantity-input border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" min="1" max="1" value="1" required>
                          </div>
                          <div>
                              <label class="block text-sm font-medium text-gray-700">Price</label>
                              <input type="text" class="price-display border-gray-300 bg-gray-100 rounded-md shadow-sm block mt-1 w-full" value="Rp 0" readonly>
                          </div>
                      </div>
                      <div class="mt-3 text-right">
                          <span class="subtotal font-medium">Subtotal: Rp 0</span>
                      </div>
                  `;
                  productsContainer.appendChild(newRow);
                  productCounter++;
                  
                  // Set up event listeners on the new row
                  setupProductRowListeners(newRow);
              });
              
              // Set up discount input event
              discountInput.addEventListener('input', calculateTotals);
              
              // Initial setup for product rows
              document.querySelectorAll('.product-row').forEach(row => {
                  setupProductRowListeners(row);
              });
              
              // Remove product event delegation
              productsContainer.addEventListener('click', function(e) {
                  if (e.target.classList.contains('remove-product')) {
                      e.target.closest('.product-row').remove();
                      calculateTotals();
                  }
              });
              
              // Function to set up event listeners for a product row
              function setupProductRowListeners(row) {
                  const productSelect = row.querySelector('.product-select');
                  const quantityInput = row.querySelector('.quantity-input');
                  const priceDisplay = row.querySelector('.price-display');
                  const subtotalEl = row.querySelector('.subtotal');
                  
                  // Product select change event
                  productSelect.addEventListener('change', function() {
                      const option = this.options[this.selectedIndex];
                      const price = option.dataset.price || 0;
                      const stock = option.dataset.stock || 0;
                      
                      // Update price display
                      priceDisplay.value = formatCurrency(price);
                      
                      // Set max attribute on quantity input based on available stock
                      quantityInput.setAttribute('max', stock);
                      
                      // Reset quantity to 1 or available stock, whichever is lower
                      if (stock < 1) {
                          quantityInput.value = 0;
                      } else {
                          quantityInput.value = 1;
                      }
                      
                      updateSubtotal(row);
                      calculateTotals();
                  });
                  
                  // Quantity input event
                  quantityInput.addEventListener('input', function() {
                      const option = productSelect.options[productSelect.selectedIndex];
                      const stock = option.dataset.stock || 0;
                      
                      // Ensure quantity doesn't exceed stock
                      const quantity = parseInt(this.value) || 0;
                      if (quantity > parseInt(stock)) {
                          this.value = stock;
                      }
                      
                      updateSubtotal(row);
                      calculateTotals();
                  });
              }
              
              // Update subtotal for a row
              function updateSubtotal(row) {
                  const productSelect = row.querySelector('.product-select');
                  const option = productSelect.options[productSelect.selectedIndex];
                  const price = option.dataset.price || 0;
                  const quantity = row.querySelector('.quantity-input').value || 0;
                  const subtotal = price * quantity;
                  
                  row.querySelector('.subtotal').textContent = `Subtotal: ${formatCurrency(subtotal)}`;
              }
              
              // Calculate all totals
              function calculateTotals() {
                  let totalAmount = 0;
                  
                  // Sum all subtotals
                  document.querySelectorAll('.product-row').forEach(row => {
                      const productSelect = row.querySelector('.product-select');
                      const option = productSelect.options[productSelect.selectedIndex];
                      const price = option.dataset.price || 0;
                      const quantity = row.querySelector('.quantity-input').value || 0;
                      totalAmount += price * quantity;
                  });
                  
                  const discountAmount = parseFloat(discountInput.value) || 0;
                  const taxableAmount = Math.max(0, totalAmount - discountAmount); // Ensure taxable amount is not negative
                  const taxAmount = taxableAmount * 0.11; // 11% tax
                  const finalAmount = taxableAmount + taxAmount;
                  
                  // Update the display elements
                  totalAmountEl.textContent = formatCurrency(totalAmount);
                  taxAmountEl.textContent = formatCurrency(taxAmount);
                  finalAmountEl.textContent = formatCurrency(finalAmount);
              }
              
              // Format currency as Rp XX.XXX
              function formatCurrency(amount) {
                  return 'Rp ' + Number(amount).toLocaleString('id-ID');
              }
          });
      </script>
  </x-app-layout>