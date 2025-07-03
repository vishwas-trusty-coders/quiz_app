<x-header />
<div class="container-top-login containe"><img src="../img/login-top-banner.png"></div>
<div class="Login-form-container container">
<div class="Login-form-left-col">
<img src="../img/register-form-new.png">
</div>
<div class="Login-form-right-col">
    <h1 class="login-heaging">Let’s Get Started </h1>
    <p class="login-text-under-heaging">Enter the details below to get your learning started</p>
    <form class="register-form" method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="flex-directions">
        
            <x-input-label for="first_name" :value="__('First Name')" />
            
            <x-text-input id="first_name" class="block mt-1 w-full" placeholder="Victor" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name" />
            <i class="fa-regular fa-user set-login-icon"></i>
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <div class="flex-directions">
            <x-input-label for="last_name" :value="__('Last Name')" />
            <x-text-input id="last_name" placeholder="James" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="last_name" />
            <i class="fa-regular fa-user set-login-icon"></i>
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4 flex-directions">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" placeholder="ifrandom@gmail.com" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <i class="fa-regular fa-envelope set-login-icon"></i>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex-directions">
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" placeholder="(1) 2536 2561 2365 " class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required autofocus autocomplete="phone" />
            <i class="fa-solid fa-phone-volume set-login-icon"></i>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4 flex-directions">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4 flex-directions">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4 login-form-btn d-flex align-items-center register-page">
                    {{ __('Register') }}
                <i class="fas fa-arrow-right ms-2"></i>
</x-primary-button>
        </div>
    </form>
</div>
</div>
<x-footer />
