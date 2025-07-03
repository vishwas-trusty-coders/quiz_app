<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between items-center">
            <!-- Dashboard Title -->
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Account Settings') }}
            </h2>

            <x-user-dashboard-header-detail />
        </div>
    </x-slot>

   



    <div class="tab-main-div">
    <div class="">
        <!-- Tabs Navigation -->
        <div class="tab-box">
            <button class="tab-button w-full py-3 text-center text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
            My Details  
            </button>
            <button class="tab-button w-full py-3 text-center text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
            Password
            </button>
            <button class="tab-button w-full py-3 text-center text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
            Delete 
            </button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content ">
            <div class="tab-pane hidden">
                <div class="">
                    <div class="">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>
            <div class="tab-pane hidden">
                <div class="">
                    <div class="">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>
            <div class="tab-pane hidden">
                <div class="">
                    <div class="">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>














</x-app-layout>
