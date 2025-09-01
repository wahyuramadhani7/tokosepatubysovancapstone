function transactionListApp() {
    return {
        transactions: [],
        filteredTransactions: [],
        paymentMethodFilter: '',
        statusFilter: '',
        dateFilter: '',
        currentPage: 1,
        perPage: 10,
        sortColumn: 'id',
        sortDirection: 'desc',
        newTransactionId: window.newTransactionId || null,
        errorMessage: '',
        loading: false,
        desktopMode: false,
        showNotesModal: false,
        currentNote: '',
        currentTransactionId: null,
        darkMode: false,

        init() {
            const savedDarkMode = localStorage.getItem('darkMode');
            this.darkMode = savedDarkMode ? JSON.parse(savedDarkMode) : window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            this.$watch('darkMode', value => {
                localStorage.setItem('darkMode', JSON.stringify(value));
                document.documentElement.classList.toggle('dark', value);
            });

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (!localStorage.getItem('darkMode')) {
                    this.darkMode = e.matches;
                }
            });

            const today = new Date().toLocaleDateString('en-CA', { timeZone: 'Asia/Jakarta' });
            this.dateFilter = today;
            this.fetchTransactions();
            if (this.newTransactionId) {
                this.$nextTick(() => {
                    this.highlightNewTransaction();
                });
            }
        },

        toggleDarkMode() {
            this.darkMode = !this.darkMode;
        },

        isMobile() {
            return window.innerWidth <= 640;
        },

        toggleDesktopMode() {
            this.desktopMode = !this.desktopMode;
            const viewport = document.getElementById('viewport');
            if (this.desktopMode) {
                viewport.setAttribute('content', 'width=1024, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes');
                document.body.classList.add('desktop-mode');
            } else {
                viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');
                document.body.classList.remove('desktop-mode');
            }
        },

        async fetchTransactions() {
            this.loading = true;
            this.errorMessage = '';
            try {
                const response = await fetch(`${window.transactionFetchUrl}?date=${this.dateFilter}&payment_method=${this.paymentMethodFilter}&status=${this.statusFilter}`);
                if (!response.ok) {
                    throw new Error(`Gagal mengambil data transaksi: ${response.status} ${response.statusText}`);
                }
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Non-JSON response:', text);
                    throw new Error('Server mengembalikan respons bukan JSON');
                }
                const data = await response.json();
                if (!data.success) {
                    throw new Error(data.message || 'Gagal mengambil data transaksi.');
                }
                const storedNotes = JSON.parse(localStorage.getItem('transactionNotes') || '{}');
                this.transactions = data.transactions.map(transaction => ({
                    ...transaction,
                    total_amount: parseFloat(transaction.total_amount) || 0,
                    final_amount: parseFloat(transaction.final_amount) || 0,
                    note: storedNotes[transaction.id] || ''
                }));
                this.sortResults(this.transactions);
                this.filteredTransactions = [...this.transactions];
                this.currentPage = 1;
            } catch (error) {
                this.errorMessage = error.message;
                this.filteredTransactions = [];
                console.error('Fetch error:', error);
            } finally {
                this.loading = false;
            }
        },

        dismissAlert() {
            const successAlert = this.$refs.successAlert;
            const errorAlert = this.$refs.errorAlert;
            if (successAlert) successAlert.remove();
            if (errorAlert) errorAlert.remove();
            this.errorMessage = '';
        },

        isNewTransaction(id) {
            return id == this.newTransactionId;
        },

        highlightNewTransaction() {
            const index = this.filteredTransactions.findIndex(t => t.id == this.newTransactionId);
            if (index >= 0) {
                this.goToPage(Math.floor(index / this.perPage) + 1);
                this.$nextTick(() => {
                    const newCard = document.querySelector(`div.bg-orange-custom\\/10`);
                    if (newCard) {
                        newCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                });
            }
        },

        formatRupiah(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount || 0);
        },

        calculateDiscount(transaction) {
            return (transaction.total_amount || 0) - (transaction.final_amount || 0);
        },

        formatTime(dateString) {
            if (!dateString) return '-';
            return new Date(dateString).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Jakarta' });
        },

        translatePaymentMethod(method, cardType) {
            const methods = {
                cash: 'Tunai',
                credit_card: 'QRIS',
                debit: cardType ? `Debit (${cardType})` : 'Debit',
                transfer: 'Transfer Bank'
            };
            return methods[method] || method;
        },

        translateStatus(status) {
            return {
                paid: 'Lunas',
                pending: 'Pending',
                cancelled: 'Dibatalkan'
            }[status] || status;
        },

        getProductNames(items) {
            if (!items || !items.length) return '<span class="text-sm text-gray-medium dark:text-gray-400">-</span>';
            return items.map(item => `
                <div class="flex items-center space-x-1">
                    <span class="text-sm text-gray-900 dark:text-gray-100 whitespace-normal overflow-wrap-break-word">
                        ${item.product && item.product.name ? item.product.name : '-'}
                    </span>
                    <span class="text-xs text-gray-medium dark:text-gray-400">
                        (${item.product && item.product.size ? item.product.size : '-'}${item.product && item.product.color ? ', ' + item.product.color : ''})
                    </span>
                </div>
            `).join('');
        },

        resetFilters() {
            this.paymentMethodFilter = '';
            this.statusFilter = '';
            this.dateFilter = new Date().toLocaleDateString('en-CA', { timeZone: 'Asia/Jakarta' });
            this.fetchTransactions();
        },

        sortResults(results) {
            results.sort((a, b) => {
                let aValue, bValue;
                switch (this.sortColumn) {
                    case 'date':
                        aValue = new Date(a.created_at);
                        bValue = new Date(b.created_at);
                        break;
                    case 'total':
                        aValue = parseFloat(a.final_amount);
                        bValue = parseFloat(b.final_amount);
                        break;
                    default:
                        aValue = a.id;
                        bValue = b.id;
                }
                return this.sortDirection === 'asc' ? (aValue < bValue ? -1 : 1) : (aValue > bValue ? -1 : 1);
            });
            return results;
        },

        sortBy(column) {
            this.sortDirection = (this.sortColumn === column && this.sortDirection === 'asc') ? 'desc' : 'asc';
            this.sortColumn = column;
            this.sortResults(this.filteredTransactions);
        },

        openNotesModal(transactionId, note) {
            this.currentTransactionId = transactionId;
            this.currentNote = note || '';
            this.showNotesModal = true;
        },

        closeNotesModal() {
            this.showNotesModal = false;
            this.currentTransactionId = null;
            this.currentNote = '';
        },

        saveNote() {
            if (this.currentTransactionId) {
                const transaction = this.transactions.find(t => t.id === this.currentTransactionId);
                if (transaction) {
                    transaction.note = this.currentNote.trim();
                    this.filteredTransactions = [...this.transactions];
                }
                const storedNotes = JSON.parse(localStorage.getItem('transactionNotes') || '{}');
                storedNotes[this.currentTransactionId] = this.currentNote.trim();
                localStorage.setItem('transactionNotes', JSON.stringify(storedNotes));
            }
            this.closeNotesModal();
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
            if (this.totalPages <= 5) {
                return Array.from({length: this.totalPages}, (_, i) => i + 1);
            }

            let pages = [];
            if (this.currentPage <= 3) {
                pages = [1, 2, 3, 4, '…', this.totalPages];
            } else if (this.currentPage >= this.totalPages - 2) {
                pages = [1, '…', this.totalPages - 3, this.totalPages - 2, this.totalPages - 1, this.totalPages];
            } else {
                pages = [1, '…', this.currentPage - 1, this.currentPage, this.currentPage + 1, '…', this.totalPages];
            }
            return pages;
        }
    };
}