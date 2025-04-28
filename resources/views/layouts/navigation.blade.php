<!-- resources/views/layouts/navigation.blade.php -->
<nav x-data="{ open: false }" class="bg-gray-900 border-b border-blue-500">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <div class="block h-10 w-10 bg-orange-600">
                            <!-- Removed the SVG to make it a solid orange square -->
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="ml-10 flex space-x-8">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 text-white {{ request()->routeIs('dashboard') ? 'font-bold' : 'hover:text-gray-300' }}">
                        {{ __('DASHBOARD') }}
                    </a>
                    
                    <a href="{{ route('inventory.index') }}" class="inline-flex items-center px-1 pt-1 text-white {{ request()->routeIs('inventory.*') ? 'font-bold' : 'hover:text-gray-300' }}">
                        {{ __('Inventory') }}
                    </a>
                    
                    <a href="#" class="inline-flex items-center px-1 pt-1 text-white hover:text-gray-300">
                        {{ __('Transaksi') }}
                    </a>
                    
                    <a href="#" class="inline-flex items-center px-1 pt-1 text-white hover:text-gray-300">
                        {{ __('Laporan') }}
                    </a>

                    @auth
                        @if (Auth::user()->role === 'employee')
                            <x-nav-link :href="route('employee.dashboard')" :active="request()->routeIs('employee.dashboard')" class="text-white hover:text-gray-300">
                                {{ __('Employee') }}
                            </x-nav-link>
                        @elseif (Auth::user()->role === 'owner')
                            <x-nav-link :href="route('owner.dashboard')" :active="request()->routeIs('owner.dashboard')" class="text-white hover:text-gray-300">
                                {{ __('Owner') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown - Preserved from original -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
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
                    <a href="{{ route('login') }}" class="text-sm text-white hover:text-gray-300 underline">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 text-sm text-white hover:text-gray-300 underline">Register</a>
                    @endif
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu - Preserved from original -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-white hover:text-gray-300 hover:bg-gray-700 hover:border-gray-600 transition duration-150 ease-in-out">
                {{ __('DASHBOARD') }}
            </a>
            
            <a href="{{ route('inventory.index') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-white hover:text-gray-300 hover:bg-gray-700 hover:border-gray-600 transition duration-150 ease-in-out">
                {{ __('Inventory') }}
            </a>
            
            <a href="#" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-white hover:text-gray-300 hover:bg-gray-700 hover:border-gray-600 transition duration-150 ease-in-out">
                {{ __('Transaksi') }}
            </a>
            
            <a href="#" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-white hover:text-gray-300 hover:bg-gray-700 hover:border-gray-600 transition duration-150 ease-in-out">
                {{ __('Laporan') }}
            </a>

            @auth
                @if (Auth::user()->role === 'employee')
                    <x-responsive-nav-link :href="route('employee.dashboard')" :active="request()->routeIs('employee.dashboard')" class="text-white hover:text-gray-300">
                        {{ __('Employee Dashboard') }}
                    </x-responsive-nav-link>
                @elseif (Auth::user()->role === 'owner')
                    <x-responsive-nav-link :href="route('owner.dashboard')" :active="request()->routeIs('owner.dashboard')" class="text-white hover:text-gray-300">
                        {{ __('Owner Dashboard') }}
                    </x-responsive-nav-link>
                @endif

                <x-responsive-nav-link :href="route('profile.edit')" class="text-white hover:text-gray-300">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();" class="text-white hover:text-gray-300">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            @else
                <div class="px-4">
                    <a href="{{ route('login') }}" class="text-sm text-white hover:text-gray-300 underline">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 text-sm text-white hover:text-gray-300 underline">Register</a>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>