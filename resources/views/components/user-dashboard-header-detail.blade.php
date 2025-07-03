<!-- Display User's Name -->
<a href="{{ route('profile.edit') }}">
    <div class="text-lg font-medium text-gray-800 dark:text-gray-200">
        
        @if(!empty(Auth::user()->profile_image))
            <img src="{{asset('storage/'. Auth::user()->profile_image) }}" width="50px" alt="profile image">
        @else
            <img src="../img/avatar.jpg" width="50px" alt="profile image">
        @endif
            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} <!-- Displays the logged-in user's name -->
    
    </div>
</a>