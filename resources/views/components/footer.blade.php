<!-- Footer Section -->
<footer>
        <!-- Newsletter Section -->
        <!-- <div class="newsletter">
            <div class="container">
                <div>
                    <h4>Newsletter</h4>
                    <h2>Subscribe To Get The Latest News</h2>
                </div>
                <div class="newsletter-input">
                    <form action="{{ route('add-to-contacts') }}" method="POST">
                        @csrf
                        <input type="email" name="email" placeholder="Enter Your Email" required>
                        <button type="submit">SUBMIT NOW</button>
                    </form>
                    @if(session('success'))
                        <div style="color: green; margin-top: 10px;">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(session('error'))
                        <div style="color: red; margin-top: 10px;">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div> -->

        <!-- Footer Content -->
        <div class="footer-container container">
            <div class="row">
                <!-- About Section -->
                <div class="col">
                <img src="{{ asset('storage/'.$footer->logo) }}" alt="Logo">
                    <p class="about-footer">{{ $footer->description }}</p>
                </div>

                <!-- Quick Links Section -->
                <div class="col quick-link">
                    <h3>Quick Links</h3>
                    <ul>
                        @foreach ($menuitems as $menuitem)
                                <li><a href="{{ $menuitem['url'] }}">{{ $menuitem['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Social Media Section -->
                <div class="col">
                    <h3>Social Media</h3>
                    <p>{{ $footer->tagline }}</p>
                    <div class="social-icons">
                        @foreach ($social_media_links as $social_media_link)
                            <a href="{{ $social_media_link['url'] }}"><i class="{{ $social_media_link['icon'] }}"></i></a>
                        @endforeach
                    </div>
                    <p><i class="fa-solid fa-envelope"></i> : <a href="mailto:{{ $footer->email }}">{{ $footer->email }}</a></p>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <p>&copy; {{ $footer->copyright }}</p>
                <div class="footer-links">
                    @foreach ($custom_links as $custom_link)
                        @if($custom_link['url'] == '#')
                            <a href="#">{{ $custom_link['name'] }}</a>
                        @else
                            <a href="{{ route($custom_link['url']) }}">{{ $custom_link['name'] }}</a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </footer>

</body>
</html>