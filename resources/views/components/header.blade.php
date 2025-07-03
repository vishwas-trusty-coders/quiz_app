<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<body class="">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <title>Quiz Generation App</title>

        <!-- Fonts -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script type="text/javascript" src="{{ asset('js/custom.js') }}?v={{ time() }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

        <!-- Owl Carousel CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

        <!-- FontAwesome for the arrow icon -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

        <!-- jQuery (required for Owl Carousel) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Owl Carousel JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Testimo  Owl Carousel JS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>


  
<script>
function myFunction() {
  var x = document.getElementById("myLinks");
  if (x.style.display === "block") {
    x.style.display = "none";
  } else {
    x.style.display = "block";
  }
}
</script>
    </head>
        <header id="header-top">
          <div class="top-bar">
            <div class="social-media">
              <div class="column">
              {{ $settings->top_header_text }}
              </div>
              <div class="column">
                <ul class="media-soc">
                  @foreach ($social_media_links as $social_media_link)
                    <li><a href="{{ $social_media_link['url'] }}"><i class="{{ $social_media_link['icon'] }}"></i></a></li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>


          <div class="container">
            <div class="menu-bar">
              <div class="logo">
                @if($logo)
                    <a href="https://www.ezmedicinellc.com/"><img src="{{ asset('storage/'.$logo) }}" alt="Logo"></a>
                @else
                    <p>Logo not available</p>
                @endif
              </div>
              
              <div class="menu-div">
                <ul>
                    @foreach ($menuItems as $menuItem)
                        <li>
                            @if(!empty($menuItem['label']) && !empty($menuItem['custom_link']))
                                <a href="{{ $menuItem['custom_link'] }}">{{ $menuItem['label'] }}</a>
                            @endif
                        </li>
                    @endforeach
                    <li class="btn-set-mobile" style="display:none;">
                      <div>
                          @auth
                              <!-- Show 'Dashboard' link if user is logged in -->
                              <a href="/dashboard">Dashboard</a>
                          @else
                              <!-- Show 'Login' link if user is not logged in -->
                              <a href="/login">Login <i class="fab fa-arrow-up-right"></i></a>
                          @endauth
                        </div>
                        <div class="regis">
                          <a href="/register">Register <i class="fas fa-arrow-right"></i> </a>
                        </div>
                    </li>
                </ul>
              </div>
              <div class="menu-container">
                  <button class="menu-toggle" aria-label="Toggle Menu">
                      &#9776; <!-- Hamburger Icon -->
                  </button>
                  <div class="menu-div">
                      <ul>
                          @foreach ($menuItems as $menuItem)
                              <li>
                                  @if(!empty($menuItem['label']) && !empty($menuItem['custom_link']))
                                      <a href="{{ $menuItem['custom_link'] }}">{{ $menuItem['label'] }}</a>
                                  @endif
                              </li>
                          @endforeach
                      </ul>
                  </div>
              </div>

              
              <div class="menu-button">
                <div class="btn-set">
                  @auth
                      <!-- Show 'Dashboard' link if user is logged in -->
                      <a href="/dashboard">Dashboard</a>
                  @else
                      <!-- Show 'Login' link if user is not logged in -->
                      <a href="/login">Login <i class="fab fa-arrow-up-right"></i></a>
                  @endauth
                </div>
                <div class="btn-set regis">
                  <a href="/register">Register <i class="fas fa-arrow-right"></i> </a>
                </div>
              </div>
            </div>
          </div>
        </header>
