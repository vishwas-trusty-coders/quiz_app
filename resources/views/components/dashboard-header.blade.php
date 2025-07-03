<div id="dashboard-header" class="dashboard-header-custom text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col items-start">
        <!-- Logo or Title -->
        <div class="text-xl font-semibold mb-4">
        <div class="logo">
                @if($logo)
                    <a href="https://www.ezmedicinellc.com/" class="text-white hover:text-gray-400">
                        <img src="{{ asset('storage/'.$logo) }}" alt="Logo">
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="text-white hover:text-gray-400">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                @endif
              </div>
            
        </div>

        <!-- Navigation Links -->
        <nav class="flex flex-col space-y-4">
            <a href="{{ route('dashboard') }}" class="hover:text-gray-400 {{ request()->routeIs('dashboard') ? 'bg-white text-gray-800' : '' }}"><i class="fa-solid fa-chalkboard side-bar-icon"></i> Dashboard</a>
            <a href="{{ route('questionbank') }}" class="hover:text-gray-400 {{ request()->routeIs('questionbank') ? 'bg-white text-gray-800' : '' }}"><i class="fa-regular fa-circle-question side-bar-icon"></i> Question Bank</a>
            <a href="{{ route('buy_credit') }}" class="hover:text-gray-400 {{ request()->routeIs('buy_credit') ? 'bg-white text-gray-800' : '' }}"><i class="fa-solid fa-credit-card side-bar-icon"></i> Buy Credits</a>
            <a href="{{ route('profile.edit') }}" class="hover:text-gray-400 {{ request()->routeIs('profile.edit') ? 'bg-white text-gray-800' : '' }}"><i class="fa-solid fa-gear side-bar-icon"></i> Account Settings</a>
            <a href="{{ route('questionbank_package') }}" class="hover:text-gray-400 {{ request()->routeIs('questionbank_package') ? 'bg-white text-gray-800' : '' }}"><i class="fa-solid fa-credit-card side-bar-icon"></i> Purchase Package</a>
            <a href="{{ route('performance.analytics') }}" class="hover:text-gray-400 {{ request()->routeIs('performance.analytics') ? 'bg-white text-gray-800' : '' }}"><i class="fa-solid fa-chart-simple side-bar-icon"></i> Performance</a>
            <a href="{{ route('logout') }}" 
                class="hover:text-gray-400"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-right-from-bracket side-bar-icon"></i> Logout
            </a>
        </nav>

        <!-- Logout Form -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</div>
