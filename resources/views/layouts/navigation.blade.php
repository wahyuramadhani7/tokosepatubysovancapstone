<nav x-data="{ open: false }" class="bg-gray-900 border-b border-blue-500">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->check() ? (auth()->user()->role === 'employee' ? route('employee.dashboard') : (auth()->user()->role === 'admin' ? route('admin.dashboard') : route('owner.dashboard'))) : route('login') }}">
                        <img src="{{ asset('images/logo2.jpg') }}" alt="Sepatu by Sovan Logo" class="h-10 w-auto sm:h-12 md:h-16">
                    </a>
                </div>
            </div>

            <!-- Centered Navigation Links - Hidden on mobile, visible on medium and up -->
            <div class="hidden md:flex items-center justify-center flex-1">
                <div class="flex space-x-4 lg:space-x-8">
                    @auth
                        @if (Auth::user()->role === 'employee')
                            <a href="{{ route('employee.dashboard') }}" class="inline-flex items-center px-1 pt-1 text-sm lg:text-base text-white {{ request()->routeIs('employee.dashboard') ? 'font-bold border-b-2 border-blue-500' : 'hover:text-gray-300' }}">
                                {{ __('Employee') }}
                            </a>
                        @elseif (Auth::user()->role === 'owner')
                            <a href="{{ route('owner.dashboard') }}" class="inline-flex items-center px-1 pt-1 text-sm lg:text-base text-white {{ request()->routeIs('owner.dashboard') ? 'font-bold border-b-2 border-blue-500' : 'hover:text-gray-300' }}">
                                {{ __('Owner') }}
                            </a>
                            <a href="{{ route('owner.employee-accounts') }}" class="inline-flex items-center px-1 pt-1 text-sm lg:text-base text-white {{ request()->routeIs('owner.employee-accounts*') ? 'font-bold border-b-2 border-blue-500' : 'hover:text-gray-300' }}">
                                {{ __('Employee Accounts') }}
                            </a>
                        @elseif (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-1 pt-1 text-sm lg:text-base text-white {{ request()->routeIs('admin.dashboard') ? 'font-bold border-b-2 border-blue-500' : 'hover:text-gray-300' }}">
                                {{ __('Admin') }}
                            </a>
                            <a href="{{ route('admin.accounts') }}" class="inline-flex items-center px-1 pt-1 text-sm lg:text-base text-white {{ request()->routeIs('admin.accounts*') ? 'font-bold border-b-2 border-blue-500' : 'hover:text-gray-300' }}">
                                {{ __('Account Management') }}
                            </a>
                        @endif
                    @endauth
                    
                    <a href="{{ route('inventory.index') }}" class="inline-flex items-center px-1 pt-1 text-sm lg:text-base text-white {{ request()->routeIs('inventory.*') ? 'font-bold border-b-2 border-blue-500' : 'hover:text-gray-300' }}">
                        {{ __('Inventory') }}
                    </a>
                    
                    <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-1 pt-1 text-sm lg:text-base text-white {{ request()->routeIs('transactions.index') || request()->routeIs('transactions.create') || request()->routeIs('transactions.edit') || request()->routeIs('transactions.show') ? 'font-bold border-b-2 border-blue-500' : 'hover:text-gray-300' }}">
                        {{ __('Transaksi') }}
                    </a>
                    
                    <a href="{{ route('visitor-monitoring.index') }}" class="inline-flex items-center px-1 pt-1 text-sm lg:text-base text-white {{ request()->routeIs('visitor-monitoring.index') ? 'font-bold border-b-2 border-blue-500' : 'hover:text-gray-300' }}">
                        {{ __('Monitoring Pengunjung') }}
                    </a>
                    
                    @if(Auth::check() && (Auth::user()->role === 'owner' || Auth::user()->role === 'admin'))
                    <a href="{{ route('transactions.report') }}" class="inline-flex items-center px-1 pt-1 text-sm lg:text-base text-white {{ request()->routeIs('transactions.report') ? 'font-bold border-b-2 border-blue-500' : 'hover:text-gray-300' }}">
                        {{ __('Laporan') }}
                    </a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown - Hidden on mobile -->
            <div class="hidden md:flex md:items-center">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex space-x-4">
                        <a href="{{ route('login') }}" class="text-sm text-white hover:text-gray-300 underline {{ request()->routeIs('login') ? 'font-bold' : '' }}">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm text-white hover:text-gray-300 underline {{ request()->routeIs('register') ? 'font-bold' : '' }}">Register</a>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Hamburger - Visible only on mobile -->
            <div class="flex items-center md:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu - Visible only when hamburger is clicked on mobile -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @if (Auth::user()->role === 'employee')
                    <a href="{{ route('employee.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-white {{ request()->routeIs('employee.dashboard') ? 'border-blue-500 font-bold bg-gray-800' : 'border-transparent hover:text-gray-300 hover:bg-gray-700 hover:border-gray-600' }} transition duration-150 ease-in-out">
                        {{ __('Employee Dashboard') }}
                    </a>
                @elseif (Auth::user()->role === 'owner')
                    <a href="{{ route('owner.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-white {{ request()->routeIs('owner.dashboard') ? 'border-blue-500 font-bold bg-gray-800' : 'border-transparent hover:text-gray-300 hover:bg-gray-700 hover:border-gray-600' }} transition duration-150 ease-in-out">
                        {{ __('Owner Dashboard') }}
                    </a>
                    <a href="{{ route('owner.employee-accounts') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-white {{ request()->routeIs('owner.employee-accounts*') ? 'border-blue-500 font-bold bg-gray-800' : 'border-transparent hover:text-gray-300 hover:bg-gray-700 hover:border-gray-600' }} transition duration-150 ease-in-out">
                        {{ __('Employee Accounts') }}
                    </a>
                @elseif (Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-white {{ request()->routeIs('admin.dashboard') ? 'border-blue-500 font-bold bg-gray-800' : 'border-transparent hover:text-gray-300 hover:bg-gray-700 hover:border-gray-600' }} transition duration-150 ease-in-out">
                        {{ __('Admin Dashboard') }}
                    </a>
                    <a href="{{ route('admin.accounts') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-white {{ request()->routeIs('admin.accounts*') ? 'border-blue-500 font-bold bg-gray-800' : 'border-transparent hover:text-gray-300 hover:bg-gray-700 hover:border-gray-600' }} transition duration-150 ease-in-out">
                        {{ __('Account Management') }}
                    </a>
                @endif
            @endauth
            
            <a href="{{ route('inventory.index') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-white {{ request()->routeIs('inventory.*') ? 'border-blue-500 font-bold bg-gray-800' : 'border-transparent hover:text-gray-300 hover:bg-gray-700 hover:border-gray-600' }} transition duration-150 ease-in-out">
                {{ __('Inventory') }}
            </a>
            
            <a href="{{ route('transactions.index') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-white {{ request()->routeIs('transactions.index') || request()->routeIs('transactions.create') || request()->routeIs('transactions.edit') || request()->routeIs('transactions.show') ? 'border-blue-500 font-bold bg-gray-800' : 'border-transparent hover:text-gray-300 hover:bg-gray-700 hover:border-gray-600' }} transition duration-150 ease-in-out">
                {{ __('Transaksi') }}
            </a>
            
            <a href="{{ route('visitor-monitoring.index') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-white {{ request()->routeIs('visitor-monitoring.index') ? 'border-blue-500 font-bold bg-gray-800' : 'border-transparent hover:text-gray-300 hover:bg-gray-700 hover:border-gray-600' }} transition duration-150 ease-in-out">
                {{ __('Monitoring Pengunjung') }}
            </a>
            
            @if(Auth::check() && (Auth::user()->role === 'owner' || Auth::user()->role === 'admin'))
            <a href="{{ route('transactions.report') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-white {{ request()->routeIs('transactions.report') ? 'border-blue-500 font-bold bg-gray-800' : 'border-transparent hover:text-gray-300 hover:bg-gray-700 hover:border-gray-600' }} transition duration-150 ease-in-out">
                {{ __('Laporan') }}
            </a>
            @endif
        </div>

        <!-- Mobile User Menu -->
        <div class="pt-4 pb-1 border-t border-gray-700">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <a href="{{ route('profile.edit') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-white hover:text-gray-300 hover:bg-gray-700 hover:border-gray-600 {{ request()->routeIs('profile.edit') ? 'border-blue-500 font-bold bg-gray-800' : '' }}">
                        {{ __('Profile') }}
                    </a>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-white hover:text-gray-300 hover:bg-gray-700 hover:border-gray-600">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            @else
                <div class="px-4 py-2 space-y-1">
                    <a href="{{ route('login') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-white {{ request()->routeIs('login') ? 'border-blue-500 font-bold bg-gray-800' : 'border-transparent hover:text-gray-300 hover:bg-gray-700 hover:border-gray-600' }}">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-white {{ request()->routeIs('register') ? 'border-blue-500 font-bold bg-gray-800' : 'border-transparent hover:text-gray-300 hover:bg-gray-700 hover:border-gray-600' }}">Register</a>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>