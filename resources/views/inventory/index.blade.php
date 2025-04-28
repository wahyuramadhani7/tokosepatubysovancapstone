<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-image: url('data:image/svg+xml;charset=utf8,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none"%3E%3Cpath fill="none" stroke="%23EEEEEE" stroke-width="1" d="M0,160L48,165.3C96,171,192,181,288,181.3C384,181,480,171,576,138.7C672,107,768,53,864,69.3C960,85,1056,171,1152,192C1248,213,1344,171,1392,149.3L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"%3E%3C/path%3E%3C/svg%3E');
            background-size: cover;
            background-position: center;
        }
        .table-row-even {
            background-color: #E2E8F0;
        }
        .table-row-odd {
            background-color: #FFFFFF;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="bg-gray-900 border-b border-blue-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="#" class="block h-10 w-10 bg-orange-600">
                            <div class="h-full w-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </div>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:ms-10 sm:flex">
                        <a href="#" class="inline-flex items-center px-1 pt-1 text-white hover:text-gray-300">
                            Dashboard
                        </a>
                        <a href="#" class="inline-flex items-center px-1 pt-1 text-white font-bold border-b-2 border-orange-600">
                            INVENTORY
                        </a>
                        <a href="#" class="inline-flex items-center px-1 pt-1 text-white hover:text-gray-300">
                            Transaksi
                        </a>
                        <a href="#" class="inline-flex items-center px-1 pt-1 text-white hover:text-gray-300">
                            Laporan
                        </a>
                    </div>
                </div>

                <!-- Right side of navbar (assumed to be profile dropdown) -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <!-- Dropdown would go here -->
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto p-4 max-w-7xl">
        <div class="mt-4 mb-6">
            <h1 class="text-2xl font-bold">MANAGEMENT INVENTORY</h1>
        </div>

        <!-- Inventory Information Cards -->
        <div class="bg-gray-800 rounded-lg p-6 mb-6">
            <h2 class="text-white text-lg font-semibold mb-4 text-center">INVENTORY INFORMATION</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Total Produk Card -->
                <div class="bg-gray-100 rounded-lg p-4 flex items-center justify-between">
                    <div>
                        <p class="text-lg font-semibold">Total Produk</p>
                    </div>
                    <div class="text-orange-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0v10l-8 4m0-10L4 7m8 4v10" />
                        </svg>
                    </div>
                </div>
                
                <!-- Stok Menipis Card -->
                <div class="bg-gray-100 rounded-lg p-4 flex items-center justify-between">
                    <div>
                        <p class="text-lg font-semibold">Stok menipis</p>
                    </div>
                    <div class="text-orange-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                </div>
                
                <!-- Total Stok Card -->
                <div class="bg-gray-100 rounded-lg p-4 flex items-center justify-between">
                    <div>
                        <p class="text-lg font-semibold">Total Stok</p>
                    </div>
                    <div class="text-orange-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Actions -->
        <div class="flex flex-col md:flex-row gap-2 mb-6">
            <div class="flex-grow">
                <div class="relative">
                    <input type="text" placeholder="Search..." class="w-full p-2 pl-10 rounded-lg bg-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-600">
                    <div class="absolute left-3 top-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <button class="bg-white p-2 rounded-lg flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah</span>
                </button>
                <button class="bg-white p-2 rounded-lg flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Riwayat</span>
                </button>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="bg-gray-800 rounded-lg p-6">
            <!-- Table Header -->
            <div class="grid grid-cols-8 gap-1 mb-2">
                <div class="bg-orange-500 text-white text-sm font-semibold p-2 rounded-sm text-center">Barcode</div>
                <div class="bg-orange-500 text-white text-sm font-semibold p-2 rounded-sm text-center">Produk</div>
                <div class="bg-orange-500 text-white text-sm font-semibold p-2 rounded-sm text-center">Kategori</div>
                <div class="bg-orange-500 text-white text-sm font-semibold p-2 rounded-sm text-center">Ukuran</div>
                <div class="bg-orange-500 text-white text-sm font-semibold p-2 rounded-sm text-center">Stok</div>
                <div class="bg-orange-500 text-white text-sm font-semibold p-2 rounded-sm text-center">Harga Beli</div>
                <div class="bg-orange-500 text-white text-sm font-semibold p-2 rounded-sm text-center">Harga Jual</div>
                <div class="bg-orange-500 text-white text-sm font-semibold p-2 rounded-sm text-center">Aksi</div>
            </div>

            <!-- Table Rows -->
            <div class="divide-y divide-gray-200">
                <!-- Row 1 -->
                <div class="grid grid-cols-8 gap-1 table-row-odd">
                    <div class="p-2 text-sm text-center">12345</div>
                    <div class="p-2 text-sm text-center">Produk A</div>
                    <div class="p-2 text-sm text-center">Kategori 1</div>
                    <div class="p-2 text-sm text-center">L</div>
                    <div class="p-2 text-sm text-center">50</div>
                    <div class="p-2 text-sm text-center">Rp 100.000</div>
                    <div class="p-2 text-sm text-center">Rp 150.000</div>
                    <div class="p-2 text-sm text-center">
                        <button class="text-blue-600 hover:text-blue-800">Edit</button>
                    </div>
                </div>
                
                <!-- Row 2 -->
                <div class="grid grid-cols-8 gap-1 table-row-even">
                    <div class="p-2 text-sm text-center">67890</div>
                    <div class="p-2 text-sm text-center">Produk B</div>
                    <div class="p-2 text-sm text-center">Kategori 2</div>
                    <div class="p-2 text-sm text-center">M</div>
                    <div class="p-2 text-sm text-center">25</div>
                    <div class="p-2 text-sm text-center">Rp 75.000</div>
                    <div class="p-2 text-sm text-center">Rp 120.000</div>
                    <div class="p-2 text-sm text-center">
                        <button class="text-blue-600 hover:text-blue-800">Edit</button>
                    </div>
                </div>
                
                <!-- Row 3 -->
                <div class="grid grid-cols-8 gap-1 table-row-odd">
                    <div class="p-2 text-sm text-center">54321</div>
                    <div class="p-2 text-sm text-center">Produk C</div>
                    <div class="p-2 text-sm text-center">Kategori 1</div>
                    <div class="p-2 text-sm text-center">S</div>
                    <div class="p-2 text-sm text-center">10</div>
                    <div class="p-2 text-sm text-center">Rp 50.000</div>
                    <div class="p-2 text-sm text-center">Rp 90.000</div>
                    <div class="p-2 text-sm text-center">
                        <button class="text-blue-600 hover:text-blue-800">Edit</button>
                    </div>
                </div>
                
                <!-- Row 4 -->
                <div class="grid grid-cols-8 gap-1 table-row-even">
                    <div class="p-2 text-sm text-center">13579</div>
                    <div class="p-2 text-sm text-center">Produk D</div>
                    <div class="p-2 text-sm text-center">Kategori 3</div>
                    <div class="p-2 text-sm text-center">XL</div>
                    <div class="p-2 text-sm text-center">30</div>
                    <div class="p-2 text-sm text-center">Rp 150.000</div>
                    <div class="p-2 text-sm text-center">Rp 200.000</div>
                    <div class="p-2 text-sm text-center">
                        <button class="text-blue-600 hover:text-blue-800">Edit</button>
                    </div>
                </div>
                
                <!-- Row 5 -->
                <div class="grid grid-cols-8 gap-1 table-row-odd">
                    <div class="p-2 text-sm text-center">24680</div>
                    <div class="p-2 text-sm text-center">Produk E</div>
                    <div class="p-2 text-sm text-center">Kategori 2</div>
                    <div class="p-2 text-sm text-center">M</div>
                    <div class="p-2 text-sm text-center">5</div>
                    <div class="p-2 text-sm text-center">Rp 85.000</div>
                    <div class="p-2 text-sm text-center">Rp 130.000</div>
                    <div class="p-2 text-sm text-center">
                        <button class="text-blue-600 hover:text-blue-800">Edit</button>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center">
                <div class="flex">
                    <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-l">
                        &lt;
                    </button>
                    <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4">
                        1
                    </button>
                    <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4">
                        2
                    </button>
                    <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4">
                        3
                    </button>
                    <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-r">
                        &gt;
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>