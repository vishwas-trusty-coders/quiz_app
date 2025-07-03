<x-header />

<!-- Session Status -->
<x-auth-session-status class="mb-4" :status="session('status')" />

<div class="container-top-login containe">
    <img src="{{ asset('img/login-top-banner.png') }}" alt="Login Top Banner">
</div>

<div class="Login-form-container container">
    <!-- Left Column -->
    <div class="Login-form-left-col">
        <img src="{{ asset('img/login-form.png') }}" alt="Login Form Image">
    </div>

    <!-- Right Column -->
    <div class="Login-form-right-col">
        <h1 class="login-heaging">Log in to your Account</h1>
        <p class="login-text-under-heaging">Enter the Details to get to your Learning Searching</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="flex-directions">
                <x-input-label for="email" :value="__('Email')" />
                <div class="relative">
                    <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 set-login-icon"></i>
                    <x-text-input id="email" class="block mt-1 w-full pl-10" type="email" placeholder="Enter your Email id" name="email" :value="old('email')" required autofocus autocomplete="username" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4 flex-directions">
                <x-input-label for="password" :value="__('Password')" />
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 set-login-icon"></i>
                    <x-text-input id="password" class="block mt-1 w-full pl-10" type="password" placeholder="Enter your Password" name="password" required autocomplete="current-password" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end mt-4 flex-directions">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button class="ms-3 login-form-btn">
                    {{ __('LOGIN NOW') }}
                </x-primary-button>
            </div>

            <!-- Sign-up Link -->
            <div class="flex items-center justify-end mt-4 flex-directions-text">
                Don’t have an account? 
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('register') }}">
                    Sign up
                </a>
            </div>
        </form>
    </div>
</div>

<x-footer />
