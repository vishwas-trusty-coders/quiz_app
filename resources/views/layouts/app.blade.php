<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/dashboard-style.css') }}?v={{ time() }}">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.2.2/circle-progress.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        

<script>

jQuery(document).ready(function () {
    jQuery('.quiz-card-description').each(function () {
        var text = jQuery(this).text();
        var words = text.split(/\s+/);
        if (words.length > 12) {
            var truncatedText = words.slice(0, 12).join(' ') + '...';
            jQuery(this).text(truncatedText);
        }
    });
});
$(document).ready(function () {
    // Select all tab buttons
    $('.tab-button').on('click', function () {
        // Remove active class from all buttons
        $('.tab-button').removeClass('active');
        
        // Add active class to the clicked button
        $(this).addClass('active');

        // Get the index of the clicked tab
        var index = $(this).index();

        // Hide all tab panes
        $('.tab-pane').removeClass('active');

        // Show the tab pane corresponding to the clicked tab
        $('.tab-pane').eq(index).addClass('active');
    });

    // Set the first tab as active by default
    $('.tab-button').first().addClass('active');
    $('.tab-pane').first().addClass('active');
});

</script>


    <script>
        $(document).ready(function () {
            $(".quiz-card").each(function () {
                var progress = $(this).find(".progress-ring").css("--progress");
                $(this).find(".fg").css("stroke-dashoffset", 314.16 - (314.16 * progress / 100));
                $(this).find(".percentage").text(progress + "%");
            });
        });
    </script>   
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex">
        <!-- Dashboard Header Component -->
        <x-dashboard-header />

        <!-- Toggle Button (Visible on both desktop and mobile) -->
        <div class="flex items-center p-4 absolute top-0 left-0 z-50">
            <button id="toggle-button" class="text-white bg-blue-500 p-2 rounded">
                <!-- Hamburger Icon -->
                <svg id="hamburger-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6 hidden">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <!-- Close Icon -->
                <svg id="close-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="flex-1 bg-white dark:bg-gray-800 dashboard-bg">
            <!-- Page Heading -->
            @isset($header)
                <header class="top-herder-dashbord" id="dashboard-header">
                    <div class="">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

<script>
    const toggleButton = document.getElementById('toggle-button');
    const header = document.getElementById('dashboard-header');
    const hamburgerIcon = document.getElementById("hamburger-icon");
    const closeIcon = document.getElementById("close-icon");

    // Initial menu state
    if (window.innerWidth >= 1024) {
        // Desktop: menu visible, close icon displayed
        header.classList.remove('hidden');
        hamburgerIcon.classList.add("hidden");
        closeIcon.classList.remove("hidden");
    } else {
        // Mobile: menu hidden, hamburger icon displayed
        header.classList.add('hidden');
        hamburgerIcon.classList.remove("hidden");
        closeIcon.classList.add("hidden");
    }

    // Toggle menu visibility on button click
    toggleButton.addEventListener('click', function () {
        header.classList.toggle('hidden'); // Show/hide menu
        hamburgerIcon.classList.toggle("hidden");
        closeIcon.classList.toggle("hidden");
        if (window.innerWidth < 1024) {
            header.classList.toggle('fixed');
        }
    });

    // Adjust menu visibility and icons on window resize
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 1024) {
            // Desktop: ensure menu is visible and close icon is displayed
            header.classList.remove('hidden');
            hamburgerIcon.classList.add("hidden");
            closeIcon.classList.remove("hidden");
        } else {
            // Mobile: ensure menu is hidden and hamburger icon is displayed
            header.classList.add('hidden');
            hamburgerIcon.classList.remove("hidden");
            closeIcon.classList.add("hidden");
        }
    });
</script>


</html>
