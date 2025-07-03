<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')


        <div class="profile-image-container ">
            <!-- Profile Image -->
            <img id="profileImagePreview" 
                src="{{ $user->profile_image ? Storage::url($user->profile_image) : asset('img/avatar.png') }}"
                alt="Profile Image"
                class="w-24 h-24 rounded-full object-cover border-2 border-gray-300 shadow-lg">
                    <div class="profile-img-label">Profile Image</div>
            <!-- Upload Icon -->
            <label for="profile_image" class="profile-cmra">
            <i class="fa-solid fa-camera"></i>
                <input id="profile_image" type="file" name="profile_image" class="hidden" accept="image/*" onchange="previewImage(event)">
            </label>
        </div>

        <div class="form-profile">
            <div>
                <x-input-label class="label-set" for="first_name" :value="__('First Name')" />
                <i class="fa-regular fa-user profile-icn"></i> <x-text-input id="first_name" name="first_name" type="text" class="profile-box-input" :value="old('first_name', $user->first_name)" required autofocus autocomplete="first_name" />
                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
            </div>

            <div>
                <x-input-label for="last_name" class="label-set" :value="__('Last Name')" />
                <i class="fa-regular fa-user profile-icn"></i> <x-text-input id="last_name" name="last_name" type="text" class="profile-box-input" :value="old('last_name', $user->last_name)" required autofocus autocomplete="last_name" />
                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
            </div>

            <div>
                <x-input-label for="email" class="label-set" :value="__('Email')" />
                <i class="fa-solid fa-envelope profile-icn"></i> <x-text-input id="email" name="email" type="email" class="profile-box-input" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

        <div>
            <x-input-label for="phone" class="label-set" :value="__('Phone Number')" />
            <i class="fa-solid fa-phone profile-icn"></i> <x-text-input id="phone" name="phone" type="text" class="profile-box-input" :value="old('phone', $user->phone)" required autofocus autocomplete="phone" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="bio" :value="__('Bio')" />
            <textarea id="bio" name="bio" rows="4" class="block mt-1 w-full">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

    </div>

        <div class="flex items-center gap-4 submit-button-profile">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('profileImagePreview');
        preview.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

