<nav x-data="{ open: false }" class="bg-white dark:bg-red-800 border-b border-gray-100 dark:border-gray-700">
    
    <!-- Primary Navigation Menu -->
    <div class="dark:bg-emerald-950 bg-green-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 ">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}">
                    <img src='PHC_Logo_White.png' alt="Logo" style="width:110px;height:60px" class="ml-2 dark:block">
                    <img src='blimage-9129-370-photo.png' alt="Logo" style="width:110px;height:60px" class="ml-2 mb-14 dark:hidden">
                </a>

                <!-- Navigation Links -->
                <div class="hidden sm:flex space-x-9 ml-10 text-gray-900 ">
                    @switch(Auth::user()->Role)
                        @case('Admin')
                            <x-nav-link :href="route('StockTransactions')" :active="request()->routeIs('StockTransactions')">
                                {{ __('Stock Transactions') }}
                            </x-nav-link>
                            <x-nav-link :href="route('showrequests')" :active="request()->routeIs('showrequests')">
                                {{ __('Requests') }}
                            </x-nav-link>
                            <x-nav-link :href="route('mainstock')" :active="request()->routeIs('mainstock')">
                                {{ __('Main Stock') }}
                            </x-nav-link>
                            <x-nav-link :href="route('pendingstock')" :active="request()->routeIs('pendingstock')">
                                {{ __('Pending Stock') }}
                            </x-nav-link>
                            <x-nav-link :href="route('getallstocks')" :active="request()->routeIs('getallstocks')">
                                {{ __('Clinics Stock') }}
                            </x-nav-link>
                            <x-nav-link :href="route('getuseroptions')" :active="request()->routeIs('getuseroptions')">
                                {{ __('Users') }}
                            </x-nav-link>
                            <x-nav-link :href="route('getcreateclinicform')" :active="request()->routeIs('getcreateclinicform')">
                                {{ __('Create Clinic') }}
                            </x-nav-link>
                            @break

                        @case('Accountant')
                            <x-nav-link :href="route('StockTransactions')" :active="request()->routeIs('StockTransactions')">
                                {{ __('Stock Transactions') }}
                            </x-nav-link>
                            <x-nav-link :href="route('showrequests')" :active="request()->routeIs('showrequests')">
                                {{ __('Requests') }}
                            </x-nav-link>
                            <x-nav-link :href="route('mainstock')" :active="request()->routeIs('mainstock')">
                                {{ __('Main Stock') }}
                            </x-nav-link>
                            <x-nav-link :href="route('batch')" :active="request()->routeIs('batch')">
                                {{ __('Reports') }}
                            </x-nav-link>
                            <x-nav-link :href="route('dishistoryadmin')" :active="request()->routeIs('dishistoryadmin')">
                                {{ __('Dispense History') }}
                            </x-nav-link>
                            @break

                            @case(null) {{-- When role is null --}}
                            <script>
                                window.location.href = "{{ 'auth/login' }}";
                            </script>
                            @break

                        @default
                            <x-nav-link :href="route('pendingstock')" :active="request()->routeIs('pendingstock')">
                                {{ __('Pending Stock') }}
                            </x-nav-link>
                            <x-nav-link :href="route('getclinicstock')" :active="request()->routeIs('getclinicstock')">
                                {{ __('Clinic Stock') }}
                            </x-nav-link>
                            <x-nav-link :href="route('stocktransfer')" :active="request()->routeIs('stocktransfer')">
                                {{ __('Stock Transfer') }}
                            </x-nav-link>
                            <x-nav-link :href="route('requeststock')" :active="request()->routeIs('requeststock')">
                                {{ __('Request Stock') }}
                            </x-nav-link>
                            <x-nav-link :href="route('dishistory')" :active="request()->routeIs('dishistory')">
                                {{ __('Dispense History') }}
                            </x-nav-link>
                            <x-nav-link :href="route('patientform')" :active="request()->routeIs('patientform')">
                                {{ __('Patients') }}
                            </x-nav-link>
                            <x-nav-link :href="route('batch')" :active="request()->routeIs('batch')">
                                {{ __('Reports') }}
                            </x-nav-link>
                    @endswitch
                </div>
                
            </div>

            <!-- User Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 ">
                <x-dropdown width="48" >
                    <x-slot name="trigger">
                        <button class="flex items-center px-3 py-2 text-sm font-medium text-black dark:text-white bg-green-200  dark:bg-green-800 rounded-md hover:bg-green-400 hover:dark:bg-green-500 hover:text-gray-700 dark:hover:text-black transition duration-150">
                            <div><i class="fas fa-user mr-2" style="color:green"></i>{{ Auth::user()->name }}</div>
                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content" class="bg-black">
                        <x-dropdown-link :href="route('profile.edit')" >{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"  onclick="event.preventDefault(); this.closest('form').submit();" >{{ __('Log Out') }}</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Menu for Mobile -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 text-gray-400 rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
            <!-- Add more links as needed -->
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1 ">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
