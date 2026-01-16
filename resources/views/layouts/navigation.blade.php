<nav x-data="{ open: false }" class="fixed top-0 w-full bg-gray-900 border-b border-blue-500 z-50 font-libre-baskerville shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ auth()->check() 
                    ? (auth()->user()->role === 'employee' 
                        ? route('employee.dashboard') 
                        : (auth()->user()->role === 'admin' 
                            ? route('admin.dashboard') 
                            : route('owner.dashboard')
                        )
                    ) 
                    : route('login') }}" class="flex items-center">
                    <img src="{{ asset('images/logo2.jpg') }}" alt="Sepatu by Sovan Logo" class="h-12 w-auto">
                </a>
            </div>

            <!-- Centered Navigation Links - Desktop & Tablet -->
            <div class="hidden md:flex items-center justify-center flex-1 px-4 lg:px-8">
                <div class="flex items-center space-x-1">
                    <!-- Dashboard -->
                    @auth
                        <a href="{{ 
                            Auth::user()->role === 'employee' ? route('employee.dashboard') :
                            (Auth::user()->role === 'admin' ? route('admin.dashboard') : route('owner.dashboard'))
                        }}" class="px-3 py-2 rounded-md text-sm font-medium text-white transition-all duration-200 cursor-pointer hover:scale-105 {{ 
                            request()->routeIs('employee.dashboard') || 
                            request()->routeIs('admin.dashboard') || 
                            request()->routeIs('owner.dashboard') 
                                ? 'bg-blue-600 shadow-md' 
                                : 'hover:bg-gray-800 hover:shadow-lg' 
                        }}">
                            Dashboard
                        </a>

                        @if(Auth::user()->role === 'owner')
                            <a href="{{ route('owner.employee-accounts') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white transition-all duration-200 cursor-pointer hover:scale-105 whitespace-nowrap {{ request()->routeIs('owner.employee-accounts*') ? 'bg-blue-600 shadow-md' : 'hover:bg-gray-800 hover:shadow-lg' }}">
                                Akun Karyawan
                            </a>
                        @endif

                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.accounts') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white transition-all duration-200 cursor-pointer hover:scale-105 whitespace-nowrap {{ request()->routeIs('admin.accounts*') ? 'bg-blue-600 shadow-md' : 'hover:bg-gray-800 hover:shadow-lg' }}">
                                Kelola Akun
                            </a>
                        @endif
                    @endauth

                    <a href="{{ route('inventory.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white transition-all duration-200 cursor-pointer hover:scale-105 {{ request()->routeIs('inventory.*') ? 'bg-blue-600 shadow-md' : 'hover:bg-gray-800 hover:shadow-lg' }}">
                        Inventory
                    </a>

                    <a href="{{ route('transactions.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white transition-all duration-200 cursor-pointer hover:scale-105 {{ 
                        request()->routeIs('transactions.index') || request()->routeIs('transactions.create') || 
                        request()->routeIs('transactions.edit') || request()->routeIs('transactions.show')
                            ? 'bg-blue-600 shadow-md' : 'hover:bg-gray-800 hover:shadow-lg' 
                    }}">
                        Transaksi
                    </a>

                    <a href="{{ route('visitor-monitoring.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white transition-all duration-200 cursor-pointer hover:scale-105 whitespace-nowrap {{ request()->routeIs('visitor-monitoring.index') ? 'bg-blue-600 shadow-md' : 'hover:bg-gray-800 hover:shadow-lg' }}">
                        Monitoring
                    </a>

                    @if(Auth::check() && in_array(Auth::user()->role, ['owner', 'admin']))
                        <a href="{{ route('transactions.report') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white transition-all duration-200 cursor-pointer hover:scale-105 {{ request()->routeIs('transactions.report') ? 'bg-blue-600 shadow-md' : 'hover:bg-gray-800 hover:shadow-lg' }}">
                            Laporan
                        </a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown - Desktop & Tablet -->
            <div class="hidden md:flex items-center">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 cursor-pointer hover:scale-105 hover:shadow-lg">
                                <span class="max-w-xs truncate">{{ Auth::user()->name }}</span>
                                <svg class="ml-2 h-4 w-4 fill-current transition-transform duration-200 group-hover:rotate-180" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-800 transition-all duration-200 cursor-pointer hover:scale-105 hover:shadow-lg {{ request()->routeIs('login') ? 'bg-blue-600 shadow-md' : '' }}">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-all duration-200 cursor-pointer hover:scale-105 shadow-md hover:shadow-xl {{ request()->routeIs('register') ? 'bg-blue-700' : '' }}">
                                Register
                            </a>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Hamburger - Mobile Only -->
            <div class="flex items-center md:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="md:hidden bg-gray-900 border-t border-gray-800">
        <div class="px-2 pt-2 pb-3 space-y-1">
            @auth
                <a href="{{ 
                    Auth::user()->role === 'employee' ? route('employee.dashboard') :
                    (Auth::user()->role === 'admin' ? route('admin.dashboard') : route('owner.dashboard'))
                }}" class="block px-3 py-2 rounded-md text-base font-medium text-white transition-all duration-200 active:scale-95 {{ 
                    request()->routeIs('employee.dashboard') || 
                    request()->routeIs('admin.dashboard') || 
                    request()->routeIs('owner.dashboard')
                        ? 'bg-blue-600 shadow-md' 
                        : 'hover:bg-gray-800'
                }}">
                    Dashboard
                </a>

                @if(Auth::user()->role === 'owner')
                    <a href="{{ route('owner.employee-accounts') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white transition-all duration-200 active:scale-95 {{ request()->routeIs('owner.employee-accounts*') ? 'bg-blue-600 shadow-md' : 'hover:bg-gray-800' }}">
                        Akun Karyawan
                    </a>
                @endif

                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.accounts') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white transition-all duration-200 active:scale-95 {{ request()->routeIs('admin.accounts*') ? 'bg-blue-600 shadow-md' : 'hover:bg-gray-800' }}">
                        Kelola Akun
                    </a>
                @endif
            @endauth

            <a href="{{ route('inventory.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white transition-all duration-200 active:scale-95 {{ request()->routeIs('inventory.*') ? 'bg-blue-600 shadow-md' : 'hover:bg-gray-800' }}">
                Inventory
            </a>

            <a href="{{ route('transactions.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white transition-all duration-200 active:scale-95 {{ 
                request()->routeIs('transactions.index') || request()->routeIs('transactions.create') || 
                request()->routeIs('transactions.edit') || request()->routeIs('transactions.show')
                    ? 'bg-blue-600 shadow-md' 
                    : 'hover:bg-gray-800'
            }}">
                Transaksi
            </a>

            <a href="{{ route('visitor-monitoring.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white transition-all duration-200 active:scale-95 {{ request()->routeIs('visitor-monitoring.index') ? 'bg-blue-600 shadow-md' : 'hover:bg-gray-800' }}">
                Monitoring Pengunjung
            </a>

            @if(Auth::check() && in_array(Auth::user()->role, ['owner', 'admin']))
                <a href="{{ route('transactions.report') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white transition-all duration-200 active:scale-95 {{ request()->routeIs('transactions.report') ? 'bg-blue-600 shadow-md' : 'hover:bg-gray-800' }}">
                    Laporan
                </a>
            @endif
        </div>

        <!-- Mobile User Menu -->
        <div class="pt-4 pb-3 border-t border-gray-800">
            @auth
                <div class="px-5 mb-3">
                    <div class="text-base font-medium text-white">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-gray-400 mt-1">{{ Auth::user()->email }}</div>
                </div>

                <div class="px-2 space-y-1">
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white transition-all duration-200 active:scale-95 {{ request()->routeIs('profile.edit') ? 'bg-blue-600 shadow-md' : 'hover:bg-gray-800' }}">
                        Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-800 transition-all duration-200 active:scale-95">
                            Log Out
                        </button>
                    </form>
                </div>
            @else
                <div class="px-2 space-y-1">
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white transition-all duration-200 active:scale-95 {{ request()->routeIs('login') ? 'bg-blue-600 shadow-md' : 'hover:bg-gray-800' }}">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white transition-all duration-200 active:scale-95 {{ request()->routeIs('register') ? 'bg-blue-600 shadow-md' : 'hover:bg-gray-800' }}">
                            Register
                        </a>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>

<!-- Padding agar konten tidak tertutup navbar fixed -->
<style>
    body {
        padding-top: 4rem;
        font-family: 'Libre Baskerville', serif;
    }
</style>