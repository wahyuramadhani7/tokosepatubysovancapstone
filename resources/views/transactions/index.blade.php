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
        .fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media print {
            body * { visibility: hidden; }
            .print-section, .print-section * { visibility: visible; }
            .print-section { position: absolute; left: 0; top: 0; width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans" x-data="transactionListApp()">
    <div class="container mx-auto p-4 max-w-7xl">
        <!-- Sukses Alert -->
        @if(session('success'))
        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded fade-in flex items-center justify-between" role="alert">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
            @if(session('transaction_id'))
            <a href="{{ route('transactions.print', session('transaction_id')) }}" target="_blank" class="text-sm text-white bg-green-600 hover:bg-green-700 px-2 py-1 rounded flex items-center">
                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak
            </a>
            @endif
            <button @click="dismissAlert" class="text-green-700 hover:text-green-900">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        @endif

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Daftar Transaksi</h1>
                <p class="text-gray-600 text-sm">Kelola transaksi penjualan sepatu</p>
            </div>
            <div class="mt-3 sm:mt-0 flex space-x-2">
                <a href="{{ Auth::user()->role === 'owner' ? route('owner.dashboard') : route('employee.dashboard') }}" class="px-3 py-2 bg-white border rounded text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('transactions.report') }}" target="_blank" class="px-3 py-2 bg-white border rounded text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Laporan
                </a>
                <a href="{{ route('transactions.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 flex items-center">
                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Transaksi Baru
                </a>
            </div>
        </div>

        <!-- Filter dan Pencarian -->
        <div class="bg-white rounded-lg shadow p-4 mb-4">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div class="sm:col-span-2">
                    <input type="text" placeholder="Cari ID, Nama..." x-model="searchQuery" @input="filterTransactions" class="w-full border rounded p-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <input type="date" x-model="dateFrom" @change="filterTransactions" class="w-full border rounded p-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <input type="date" x-model="dateTo" @change="filterTransactions" class="w-full border rounded p-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-3">
                <select x-model="paymentMethodFilter" @change="filterTransactions" class="border rounded p-2 text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Metode</option>
                    <option value="cash">Tunai</option>
                    <option value="credit_card">Kartu Kredit</option>
                    <option value="transfer">Transfer</option>
                </select>
                <select x-model="statusFilter" @change="filterTransactions" class="border rounded p-2 text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="paid">Lunas</option>
                    <option value="pending">Pending</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
                <button @click="resetFilters" class="px-3 py-2 bg-white border rounded text-sm text-gray-700 hover:bg-gray-100">Reset</button>
            </div>
        </div>

        <!-- Tabel Transaksi -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" @click="sortBy('id')">ID <span x-show="sortColumn === 'id'" x-text="sortDirection === 'asc' ? '▲' : '▼'"></span></th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" @click="sortBy('date')">Tanggal <span x-show="sortColumn === 'date'" x-text="sortDirection === 'asc' ? '▲' : '▼'"></span></th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Metode</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" @click="sortBy('total')">Total <span x-show="sortColumn === 'total'" x-text="sortDirection === 'asc' ? '▲' : '▼'"></span></th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" x-show="filteredTransactions.length > 0">
                    <template x-for="transaction in paginatedTransactions" :key="transaction.id">
                        <tr :class="{'bg-green-50': isNewTransaction(transaction.id)}" class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-blue-600" x-text="transaction.invoice_number || ('TRX-' + transaction.id.toString().padStart(6, '0'))"></td>
                            <td class="px-4 py-3 text-sm text-gray-600" x-text="formatDate(transaction.created_at || transaction.date)"></td>
                            <td class="px-4 py-3 text-sm">
                                <div x-text="transaction.customer_name || 'Tanpa Nama'"></div>
                                <div class="text-gray-500" x-text="transaction.customer_phone || '-'"></div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 rounded text-xs" :class="{
                                    'bg-green-100 text-green-800': transaction.payment_method === 'cash',
                                    'bg-blue-100 text-blue-800': transaction.payment_method === 'credit_card',
                                    'bg-purple-100 text-purple-800': transaction.payment_method === 'transfer'
                                }" x-text="translatePaymentMethod(transaction.payment_method)"></span>
                            </td>
                            <td class="px-4 py-3 text-sm font-medium" x-text="formatRupiah(transaction.final_amount || transaction.total)"></td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 rounded text-xs" :class="{
                                    'bg-green-100 text-green-800': transaction.payment_status === 'paid' || transaction.status === 'completed',
                                    'bg-yellow-100 text-yellow-800': transaction.payment_status === 'pending' || transaction.status === 'pending',
                                    'bg-red-100 text-red-800': transaction.payment_status === 'cancelled' || transaction.status === 'cancelled'
                                }" x-text="translateStatus(transaction.payment_status || transaction.status || 'paid')"></span>
                            </td>
                            <td class="px-4 py-3 text-right text-sm">
                                <a :href="'{{ url('/transactions') }}/' + transaction.id + '/print'" target="_blank" class="text-gray-600 hover:text-gray-900 mr-2">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                </a>
                                <button @click="confirmDelete(transaction)" class="text-red-600 hover:text-red-900">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <div x-show="filteredTransactions.length === 0" class="text-center py-8">
                <svg class="h-10 w-10 mx-auto text-gray-400 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-600">Tidak ada transaksi</p>
            </div>
        </div>

        <!-- Pagination -->
        <div x-show="filteredTransactions.length > 0" class="flex items-center justify-between bg-white p-3 mt-4 rounded-lg shadow">
            <p class="text-sm text-gray-600">
                Menampilkan <span x-text="paginationFrom()"></span> - <span x-text="paginationTo()"></span> dari <span x-text="filteredTransactions.length"></span>
            </p>
            <div class="flex items-center space-x-2">
                <select x-model.number="perPage" class="border rounded p-1 text-sm">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
                <button @click="prevPage" :disabled="currentPage === 1" class="px-2 py-1 border rounded text-sm" :class="{'opacity-50 cursor-not-allowed': currentPage === 1}">&lt;</button>
                <template x-for="page in displayedPages()" :key="page">
                    <button @click="goToPage(page)" class="px-2 py-1 border rounded text-sm" :class="page === currentPage ? 'bg-blue-500 text-white' : 'bg-white'"><span x-text="page"></span></button>
                </template>
                <button @click="nextPage" :disabled="currentPage >= totalPages" class="px-2 py-1 border rounded text-sm" :class="{'opacity-50 cursor-not-allowed': currentPage >= totalPages}">&gt;</button>
            </div>
        </div>

        <!-- Delete Modal -->
        <div x-show="showDeleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50" x-cloak>
            <div class="bg-white rounded-lg p-5 max-w-md w-full">
                <div class="flex items-start">
                    <svg class="h-8 w-8 text-red-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Hapus Transaksi</h3>
                        <p class="text-sm text-gray-600 mt-1">Yakin ingin menghapus transaksi <span class="font-bold" x-text="transactionToDelete ? (transactionToDelete.invoice_number || ('TRX-' + transactionToDelete.id.toString().padStart(6, '0'))) : ''"></span>? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>
                <div class="mt-4 flex justify-end space-x-2">
                    <form :action="'{{ url('/transactions') }}/' + (transactionToDelete ? transactionToDelete.id : '')" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm">Hapus</button>
                    </form>
                    <button @click="showDeleteModal = false" class="px-3 py-2 bg-white border rounded text-sm text-gray-700 hover:bg-gray-100">Batal</button>
                </div>
            </div>
        </div>

        <!-- Floating Print Button -->
        <div x-show="newTransactionId" class="fixed bottom-4 right-4" x-cloak>
            <a :href="'{{ url('/transactions') }}/' + newTransactionId + '/print'" target="_blank" class="p-3 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                newTransactionId: @json(session('transaction_id') ?? null),

                init() {
                    const newTransaction = @json(session('new_transaction') ?? null);
                    if (newTransaction && !this.transactions.find(t => t.id === newTransaction.id)) {
                        this.transactions.unshift(newTransaction);
                    }
                    this.filterTransactions();
                    if (this.newTransactionId) {
                        this.$nextTick(() => this.highlightNewTransaction());
                    }
                },

                dismissAlert() {
                    document.querySelector('.fade-in')?.remove();
                },

                isNewTransaction(id) {
                    return id == this.newTransactionId;
                },

                highlightNewTransaction() {
                    const index = this.transactions.findIndex(t => t.id == this.newTransactionId);
                    if (index >= 0) {
                        this.goToPage(Math.floor(index / this.perPage) + 1);
                        this.$nextTick(() => {
                            document.querySelector(`tr.bg-green-50`)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        });
                    }
                },

                formatRupiah(amount) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
                },

                formatDate(dateString) {
                    if (!dateString) return '-';
                    return new Date(dateString).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
                },

                translatePaymentMethod(method) {
                    return { cash: 'Tunai', credit_card: 'Kartu Kredit', transfer: 'Transfer Bank' }[method] || method;
                },

                translateStatus(status) {
                    return { paid: 'Lunas', completed: 'Selesai', pending: 'Pending', cancelled: 'Dibatalkan' }[status] || status;
                },

                resetFilters() {
                    this.searchQuery = this.dateFrom = this.dateTo = this.paymentMethodFilter = this.statusFilter = '';
                    this.filterTransactions();
                },

                filterTransactions() {
                    let results = [...this.transactions];

                    if (this.searchQuery) {
                        const query = this.searchQuery.toLowerCase();
                        results = results.filter(t =>
                            (t.id?.toString().includes(query)) ||
                            (t.invoice_number?.toLowerCase().includes(query)) ||
                            (t.customer_name?.toLowerCase().includes(query)) ||
                            (t.customer_phone?.toLowerCase().includes(query))
                        );
                    }

                    if (this.dateFrom) {
                        const fromDate = new Date(this.dateFrom);
                        results = results.filter(t => new Date(t.created_at || t.date) >= fromDate);
                    }

                    if (this.dateTo) {
                        const toDate = new Date(this.dateTo).setHours(23, 59, 59, 999);
                        results = results.filter(t => new Date(t.created_at || t.date) <= toDate);
                    }

                    if (this.paymentMethodFilter) {
                        results = results.filter(t => t.payment_method === this.paymentMethodFilter);
                    }

                    if (this.statusFilter) {
                        results = results.filter(t => t.payment_status === this.statusFilter || t.status === this.statusFilter);
                    }

                    results.sort((a, b) => {
                        let aValue, bValue;
                        switch (this.sortColumn) {
                            case 'date':
                                aValue = new Date(a.created_at || a.date);
                                bValue = new Date(b.created_at || b.date);
                                break;
                            case 'total':
                                aValue = parseFloat(a.final_amount || a.total);
                                bValue = parseFloat(b.final_amount || b.total);
                                break;
                            default:
                                aValue = a.id;
                                bValue = b.id;
                        }
                        return this.sortDirection === 'asc' ? aValue - bValue : bValue - aValue;
                    });

                    this.filteredTransactions = results;
                    this.currentPage = 1;
                },

                sortBy(column) {
                    this.sortColumn = column;
                    this.sortDirection = this.sortColumn === column && this.sortDirection === 'asc' ? 'desc' : 'asc';
                    this.filterTransactions();
                },

                get totalPages() {
                    return Math.ceil(this.filteredTransactions.length / this.perPage);
                },

                get paginatedTransactions() {
                    const start = (this.currentPage - 1) * this.perPage;
                    return this.filteredTransactions.slice(start, start + this.perPage);
                },

                prevPage() {
                    if (this.currentPage > 1) this.currentPage--;
                },

                nextPage() {
                    if (this.currentPage < this.totalPages) this.currentPage++;
                },

                goToPage(page) {
                    if (typeof page === 'number') this.currentPage = Math.min(Math.max(1, page), this.totalPages);
                },

                paginationFrom() {
                    return this.filteredTransactions.length ? (this.currentPage - 1) * this.perPage + 1 : 0;
                },

                paginationTo() {
                    return Math.min(this.currentPage * this.perPage, this.filteredTransactions.length);
                },

                displayedPages() {
                    const pages = [];
                    const total = this.totalPages;
                    let start = Math.max(1, this.currentPage - 1);
                    let end = Math.min(total, this.currentPage + 1);

                    if (total <= 3) {
                        for (let i = 1; i <= total; i++) pages.push(i);
                    } else {
                        if (this.currentPage > 2) pages.push(1);
                        if (this.currentPage > 3) pages.push('...');
                        for (let i = start; i <= end; i++) pages.push(i);
                        if (this.currentPage < total - 2) pages.push('...');
                        if (this.currentPage < total - 1) pages.push(total);
                    }
                    return pages;
                },

                confirmDelete(transaction) {
                    this.transactionToDelete = transaction;
                    this.showDeleteModal = true;
                }
            }
        }
    </script>
</body>
</html>