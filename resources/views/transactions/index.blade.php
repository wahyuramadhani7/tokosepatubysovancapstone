<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi - Sepatu by Sovan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.min.js" defer></script>
    <style>
        [x-cloak] { display: none !important; }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .new-transaction-alert {
            animation: fadeIn 0.5s ease-out;
        }
        
        @media print {
            body * {
                visibility: hidden;
            }
            .print-section, .print-section * {
                visibility: visible;
            }
            .print-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8" x-data="transactionListApp()">
        <!-- Sukses Alert untuk Transaksi Baru -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-md new-transaction-alert" role="alert">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
                <!-- Jika ada transaction_id dalam session, tampilkan tombol cetak -->
                @if(session('transaction_id'))
                <div class="ml-auto">
                    <a href="{{ route('transactions.print', session('transaction_id')) }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-green-600 hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green active:bg-green-700 transition duration-150 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Invoice
                    </a>
                </div>
                @endif
                <button @click="dismissAlert" class="ml-4 text-green-700 hover:text-green-900 focus:outline-none">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Daftar Transaksi</h1>
                <p class="text-gray-600 mt-2">Kelola semua transaksi penjualan sepatu.</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-2">
                <a href="{{ Auth::user()->role === 'owner' ? route('owner.dashboard') : route('employee.dashboard') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                     </svg>
                     Kembali ke Dashboard
                 </a>
                 
                <!-- Tombol Cetak Laporan -->
                <a href="{{ route('transactions.report') }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Laporan
                </a>
                
                <a href="{{ route('transactions.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Buat Transaksi Baru
                </a>
            </div>
        </div>

        <!-- Filter dan Pencarian -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Pencarian -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Transaksi</label>
                    <div class="relative">
                        <input type="text" id="search" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="ID Transaksi, Nama Pelanggan, dll..." x-model="searchQuery" @input="filterTransactions">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Filter Tanggal -->
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" id="date_from" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" x-model="dateFrom" @change="filterTransactions">
                </div>

                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" id="date_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" x-model="dateTo" @change="filterTransactions">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <!-- Filter Metode Pembayaran -->
                <div>
                    <label for="payment_method_filter" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                    <select id="payment_method_filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" x-model="paymentMethodFilter" @change="filterTransactions">
                        <option value="">Semua Metode</option>
                        <option value="cash">Tunai</option>
                        <option value="credit_card">Kartu Kredit</option>
                        <option value="transfer">Transfer Bank</option>
                    </select>
                </div>

                <!-- Filter Status -->
                <div>
                    <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-1">Status Transaksi</label>
                    <select id="status_filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" x-model="statusFilter" @change="filterTransactions">
                        <option value="">Semua Status</option>
                        <option value="paid">Lunas</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </div>

                <!-- Reset Filter -->
                <div class="flex items-end">
                    <button type="button" @click="resetFilters" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabel Transaksi -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" @click="sortBy('id')">
                                <div class="flex items-center cursor-pointer">
                                    ID
                                    <span class="ml-1" x-show="sortColumn === 'id'">
                                        <template x-if="sortDirection === 'asc'">▲</template>
                                        <template x-if="sortDirection === 'desc'">▼</template>
                                    </span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" @click="sortBy('date')">
                                <div class="flex items-center cursor-pointer">
                                    Tanggal
                                    <span class="ml-1" x-show="sortColumn === 'date'">
                                        <template x-if="sortDirection === 'asc'">▲</template>
                                        <template x-if="sortDirection === 'desc'">▼</template>
                                    </span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" @click="sortBy('customer')">
                                <div class="flex items-center cursor-pointer">
                                    Pelanggan
                                    <span class="ml-1" x-show="sortColumn === 'customer'">
                                        <template x-if="sortDirection === 'asc'">▲</template>
                                        <template x-if="sortDirection === 'desc'">▼</template>
                                    </span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Metode Bayar
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" @click="sortBy('total')">
                                <div class="flex items-center cursor-pointer">
                                    Total
                                    <span class="ml-1" x-show="sortColumn === 'total'">
                                        <template x-if="sortDirection === 'asc'">▲</template>
                                        <template x-if="sortDirection === 'desc'">▼</template>
                                    </span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" x-show="filteredTransactions.length > 0">
                        <template x-for="transaction in paginatedTransactions" :key="transaction.id">
                            <tr :class="{'bg-green-50': isNewTransaction(transaction.id)}" class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600" x-text="transaction.invoice_number || ('TRX-' + transaction.id.toString().padStart(6, '0'))"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="formatDate(transaction.created_at || transaction.date)"></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900" x-text="transaction.customer_name || 'Tanpa Nama'"></div>
                                    <div class="text-sm text-gray-500" x-text="transaction.customer_phone || '-'"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                        :class="{
                                            'bg-green-100 text-green-800': transaction.payment_method === 'cash',
                                            'bg-blue-100 text-blue-800': transaction.payment_method === 'credit_card',
                                            'bg-purple-100 text-purple-800': transaction.payment_method === 'transfer'
                                        }"
                                        x-text="translatePaymentMethod(transaction.payment_method)">
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="formatRupiah(transaction.final_amount || transaction.total)"></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                        :class="{
                                            'bg-green-100 text-green-800': transaction.payment_status === 'paid' || transaction.status === 'completed',
                                            'bg-yellow-100 text-yellow-800': transaction.payment_status === 'pending' || transaction.status === 'pending',
                                            'bg-red-100 text-red-800': transaction.payment_status === 'cancelled' || transaction.status === 'cancelled'
                                        }"
                                        x-text="translateStatus(transaction.payment_status || transaction.status || 'paid')">
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <!-- Print Invoice -->
                                        <a :href="'{{ url('/transactions') }}/' + transaction.id + '/print'" target="_blank" class="text-gray-600 hover:text-gray-900 tooltip" title="Cetak Invoice">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                        </a>
                                        
                                        <!-- Delete -->
                                        <button @click="confirmDelete(transaction)" class="text-red-600 hover:text-red-900 tooltip" title="Hapus Transaksi">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                
                <!-- Empty State -->
                <div x-show="filteredTransactions.length === 0" class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-600">Tidak ada transaksi yang ditemukan</p>
                    <p class="text-gray-500 text-sm mt-1">Coba ubah filter atau buat transaksi baru</p>
                </div>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="flex items-center justify-between bg-white px-4 py-3 border-t border-gray-200 sm:px-6 rounded-lg shadow-md" x-show="filteredTransactions.length > 0">
            <div class="flex-1 flex justify-between sm:hidden">
                <button @click="prevPage" :disabled="currentPage === 1" :class="{'opacity-50 cursor-not-allowed': currentPage === 1}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Sebelumnya
                </button>
                <button @click="nextPage" :disabled="currentPage >= totalPages" :class="{'opacity-50 cursor-not-allowed': currentPage >= totalPages}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Berikutnya
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Menampilkan
                        <span class="font-medium" x-text="paginationFrom()"></span>
                        sampai
                        <span class="font-medium" x-text="paginationTo()"></span>
                        dari
                        <span class="font-medium" x-text="filteredTransactions.length"></span>
                        hasil
                    </p>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <label for="perPage" class="text-sm text-gray-700">Per Halaman:</label>
                        <select id="perPage" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm" x-model.number="perPage">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                            <option>100</option>
                        </select>
                    </div>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <button @click="goToPage(1)" :disabled="currentPage === 1" :class="{'opacity-50 cursor-not-allowed': currentPage === 1}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">First</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                            </svg>
                        </button>
                        <button @click="prevPage" :disabled="currentPage === 1" :class="{'opacity-50 cursor-not-allowed': currentPage === 1}" class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Previous</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        
                        <template x-for="page in displayedPages()" :key="page">
                            <button @click="goToPage(page)" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium"
                                :class="page === currentPage ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white text-gray-500 hover:bg-gray-50'">
                                <span x-text="page"></span>
                            </button>
                        </template>
                        
                        <button @click="nextPage" :disabled="currentPage >= totalPages" :class="{'opacity-50 cursor-not-allowed': currentPage >= totalPages}" class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Next</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <button @click="goToPage(totalPages)" :disabled="currentPage >= totalPages" :class="{'opacity-50 cursor-not-allowed': currentPage >= totalPages}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Last</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                            </svg>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
        
        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" class="fixed inset-0 overflow-y-auto" style="z-index: 50;" x-cloak>
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showDeleteModal = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
                
                <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-cap
                                    line="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Hapus Transaksi
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Apakah Anda yakin ingin menghapus transaksi <span class="font-bold" x-text="transactionToDelete ? (transactionToDelete.invoice_number || ('TRX-' + transactionToDelete.id.toString().padStart(6, '0'))) : ''"></span>? Semua data yang terkait dengan transaksi ini akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <form :action="'{{ url('/transactions') }}/' + (transactionToDelete ? transactionToDelete.id : '')" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Hapus
                            </button>
                        </form>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="showDeleteModal = false">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Print Preview Modal untuk Quick Print -->
        <div x-show="showPrintPreview" class="fixed inset-0 overflow-y-auto" style="z-index: 50;" x-cloak>
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                <div x-show="showPrintPreview" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showPrintPreview = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <div x-show="showPrintPreview" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Preview Invoice
                            </h3>
                            <div class="flex space-x-3">
                                <button @click="printInvoice()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    Cetak
                                </button>
                                <button @click="showPrintPreview = false" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Tutup
                                </button>
                            </div>
                        </div>
                        <div class="border rounded-lg p-4 max-h-[70vh] overflow-y-auto print-section" id="printArea">
                            <div x-html="printPreviewContent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Floating Print Button untuk Transaksi Baru -->
        <div x-show="newTransactionId" x-transition class="fixed bottom-6 right-6" x-cloak>
            <a :href="'{{ url('/transactions') }}/' + newTransactionId + '/print'" target="_blank" class="flex items-center justify-center p-4 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
            </a>
        </div>
    </div>

    <script>
        function transactionListApp() {
            return {
                transactions: @json($transactions->items() ?? []),
                filteredTransactions: [],
                searchQuery: '',
                dateFrom: '',
                dateTo: '',
                paymentMethodFilter: '',
                statusFilter: '',
                currentPage: 1,
                perPage: 10,
                sortColumn: 'id',
                sortDirection: 'desc',
                showDeleteModal: false,
                transactionToDelete: null,
                showPrintPreview: false,
                printPreviewContent: '',
                newTransactionId: @json(session('transaction_id') ?? null),
                
                init() {
                    // Tambahkan transaksi baru dari session jika ada
                    const newTransaction = @json(session('new_transaction') ?? null);
                    if (newTransaction) {
                        if (!this.transactions.find(t => t.id === newTransaction.id)) {
                            this.transactions.unshift(newTransaction);
                        }
                    }
                    
                    this.filterTransactions();
                    
                    // Auto focus ke transaksi baru jika ada
                    if (this.newTransactionId) {
                        this.$nextTick(() => {
                            this.highlightNewTransaction();
                        });
                    }
                },
                
                // Menghapus alert success
                dismissAlert() {
                    const alert = document.querySelector('.new-transaction-alert');
                    if (alert) {
                        alert.remove();
                    }
                },
                
                // Cek apakah transaksi adalah yang baru dibuat
                isNewTransaction(id) {
                    return id == this.newTransactionId;
                },
                
                // Highlight dan scroll ke transaksi baru
                highlightNewTransaction() {
                    const newTransactionIndex = this.transactions.findIndex(t => t.id == this.newTransactionId);
                    if (newTransactionIndex >= 0) {
                        const pageOfNewTransaction = Math.floor(newTransactionIndex / this.perPage) + 1;
                        this.goToPage(pageOfNewTransaction);
                        this.$nextTick(() => {
                            const newTransactionRow = document.querySelector(`tr.bg-green-50`);
                            if (newTransactionRow) {
                                newTransactionRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        });
                    }
                },
                
                // Format number to currency
                formatRupiah(amount) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
                },
                
                // Format date to Indonesian format
                formatDate(dateString) {
                    if (!dateString) return '-';
                    const options = { year: 'numeric', month: 'long', day: 'numeric' };
                    return new Date(dateString).toLocaleDateString('id-ID', options);
                },
                
                // Translate payment method to Indonesian
                translatePaymentMethod(method) {
                    const translations = {
                        'cash': 'Tunai',
                        'credit_card': 'Kartu Kredit',
                        'transfer': 'Transfer Bank'
                    };
                    return translations[method] || method;
                },
                
                // Translate status to Indonesian
                translateStatus(status) {
                    const translations = {
                        'paid': 'Lunas',
                        'completed': 'Selesai',
                        'pending': 'Pending',
                        'cancelled': 'Dibatalkan'
                    };
                    return translations[status] || status;
                },
                
                // Reset all filters
                resetFilters() {
                    this.searchQuery = '';
                    this.dateFrom = '';
                    this.dateTo = '';
                    this.paymentMethodFilter = '';
                    this.statusFilter = '';
                    this.filterTransactions();
                },
                
                // Apply all filters
                filterTransactions() {
                    let results = [...this.transactions];
                    
                    if (this.searchQuery) {
                        const query = this.searchQuery.toLowerCase();
                        results = results.filter(transaction => 
                            (transaction.id && transaction.id.toString().includes(query)) ||
                            (transaction.invoice_number && transaction.invoice_number.toLowerCase().includes(query)) ||
                            (transaction.customer_name && transaction.customer_name.toLowerCase().includes(query)) ||
                            (transaction.customer_phone && transaction.customer_phone.toLowerCase().includes(query)) ||
                            (transaction.customer_email && transaction.customer_email.toLowerCase().includes(query))
                        );
                    }
                    
                    if (this.dateFrom) {
                        const fromDate = new Date(this.dateFrom);
                        fromDate.setHours(0, 0, 0, 0);
                        results = results.filter(transaction => {
                            const txDate = new Date(transaction.created_at || transaction.date);
                            return txDate >= fromDate;
                        });
                    }
                    
                    if (this.dateTo) {
                        const toDate = new Date(this.dateTo);
                        toDate.setHours(23, 59, 59, 999);
                        results = results.filter(transaction => {
                            const txDate = new Date(transaction.created_at || transaction.date);
                            return txDate <= toDate;
                        });
                    }
                    
                    if (this.paymentMethodFilter) {
                        results = results.filter(transaction => transaction.payment_method === this.paymentMethodFilter);
                    }
                    
                    if (this.statusFilter) {
                        results = results.filter(transaction => 
                            transaction.payment_status === this.statusFilter || 
                            transaction.status === this.statusFilter
                        );
                    }
                    
                    results.sort((a, b) => {
                        let aValue, bValue;
                        switch(this.sortColumn) {
                            case 'date':
                                aValue = new Date(a.created_at || a.date);
                                bValue = new Date(b.created_at || b.date);
                                break;
                            case 'customer':
                                aValue = a.customer_name || '';
                                bValue = b.customer_name || '';
                                break;
                            case 'total':
                                aValue = parseFloat(a.final_amount || a.total);
                                bValue = parseFloat(b.final_amount || b.total);
                                break;
                            default:
                                aValue = a.id;
                                bValue = b.id;
                        }
                        if (aValue < bValue) {
                            return this.sortDirection === 'asc' ? -1 : 1;
                        }
                        if (aValue > bValue) {
                            return this.sortDirection === 'asc' ? 1 : -1;
                        }
                        return 0;
                    });
                    
                    this.filteredTransactions = results;
                    this.currentPage = 1;
                },
                
                // Sorting transactions
                sortBy(column) {
                    if (this.sortColumn === column) {
                        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sortColumn = column;
                        this.sortDirection = 'asc';
                    }
                    this.filterTransactions();
                },
                
                // Pagination methods
                get totalPages() {
                    return Math.ceil(this.filteredTransactions.length / this.perPage);
                },
                
                get paginatedTransactions() {
                    const start = (this.currentPage - 1) * this.perPage;
                    const end = start + this.perPage;
                    return this.filteredTransactions.slice(start, end);
                },
                
                prevPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                    }
                },
                
                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.currentPage++;
                    }
                },
                
                goToPage(page) {
                    if (typeof page === 'number') {
                        this.currentPage = Math.min(Math.max(1, page), this.totalPages);
                    }
                },
                
                paginationFrom() {
                    if (this.filteredTransactions.length === 0) return 0;
                    return (this.currentPage - 1) * this.perPage + 1;
                },
                
                paginationTo() {
                    if (this.filteredTransactions.length === 0) return 0;
                    return Math.min(this.currentPage * this.perPage, this.filteredTransactions.length);
                },
                
                displayedPages() {
                    const totalPages = this.totalPages;
                    const currentPage = this.currentPage;
                    const pages = [];
                    
                    if (totalPages <= 5) {
                        for (let i = 1; i <= totalPages; i++) {
                            pages.push(i);
                        }
                    } else {
                        pages.push(1);
                        let startPage = Math.max(2, currentPage - 1);
                        let endPage = Math.min(totalPages - 1, currentPage + 1);
                        if (currentPage <= 3) {
                            endPage = 4;
                        } else if (currentPage >= totalPages - 2) {
                            startPage = totalPages - 3;
                        }
                        if (startPage > 2) {
                            pages.push('...');
                        }
                        for (let i = startPage; i <= endPage; i++) {
                            pages.push(i);
                        }
                        if (endPage < totalPages - 1) {
                            pages.push('...');
                        }
                        pages.push(totalPages);
                    }
                    return pages;
                },
                
                // Delete confirmation
                confirmDelete(transaction) {
                    this.transactionToDelete = transaction;
                    this.showDeleteModal = true;
                },
                
                // Tampilkan preview invoice dan cetak
                showInvoicePreview(transactionId) {
                    fetch(`{{ url('/transactions') }}/${transactionId}/print?format=html`)
                        .then(response => response.text())
                        .then(html => {
                            this.printPreviewContent = html;
                            this.showPrintPreview = true;
                        });
                },
                
                // Cetak invoice dari preview
                printInvoice() {
                    window.print();
                }
            }
        }
    </script>
</body>
</html>