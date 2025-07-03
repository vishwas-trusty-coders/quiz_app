<x-header />



<div id="hero-sec" class="back-img">
    <div class="row">
        <div class="container our-services podcast">
            <h2>{{ $podcastpage->title }}</h2>
            <p>{{ $podcastpage->description }}</p>
        </div>
    </div>
</div>


<div id="study-buddy-sec" class="study-buddy-set">
    <div class="row">
        <div class="container">
            <div class="column1"><img src="{{ asset('storage/'.$podcastpage->section1_image) }}" alt=""></div>
            <div class="column2">
            
                    <div class="how-set-title">
                        <h3>{{ $podcastpage->section1_title }}</h3>
                        <span class="sub-title">{{ $podcastpage->section1_subtitle }}</span>
                        <p class="study-despction">{{ $podcastpage->section1_description }}</p>
                    </div>
                    <div class="podcast-buttons">
                            <a href="https://apple.com" class="podcast-button apple" target="_blank">
                              <div><img src="../img/podcast-img.png" alt=""></div>
                              <div><span>Listen on </span><br>
                                <span>Apple Podcasts</span></div>
                            </a>
                            <a href="https://apple.com" class="podcast-button apple" target="_blank">
                              <div><img src="../img/iphone-set.png" alt=""></div>
                              <div><span>Listen on </span><br>
                                <span>Apple Podcasts</span></div>
                            </a>
                    </div>

            </div>
        </div>
    </div>
</div>


@if(!empty($podcastpage->podcast_details))
    @foreach($podcastpage->podcast_details as $podcast_details)
        <div id="podcast-box">
            <div class="row">
                <div class="container">
                    <div class="column-podcost">
                        <img src="{{ asset('storage/'.$podcast_details['image']) }}" alt="">
                    </div>
                    <div class="column-podcost">
                        <h2>{{ $podcast_details['title'] }}</h2>
                        <p class="study-despction">{{ $podcast_details['description'] }}</p>

                        <div class="episode-info">
                            <div class="icon">
                                <i class="fa-regular fa-calendar"></i>
                                {{ $podcast_details['date'] }}
                            </div>
                            <div class="icon">
                                <i class="fa-regular fa-clock"></i>
                                {{ $podcast_details['duration'] }}
                            </div>
                            <button class="button">Latest Episode</button>
                        </div>
                        <a href="{{ $podcast_details['video_link'] }}" target="_blank" class="play-button">
                                <div class="icon"></div>
                                Play Podcast
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif


<div id="Speakers-sec">
    <div class="row">
        <div class="container">
            <div class="speaker-title">{{ $podcastpage->section_title }}</div>

            <div class="card-container">
                @if(!empty($podcastpage->guest_details))
                    @foreach($podcastpage->guest_details as $guest_details)
                        <!-- Card 1 -->
                        <div class="card" style="background: url({{ asset('storage/'.$guest_details['image']) }}); background-size: cover; background-position: center;">
                            <div class="card-content">
                                <h3>{{ $guest_details['title'] }}</h3>
                                <a href="{{ $guest_details['video_link'] }}" target="_blank" class="play-button guest_button">
                                        <div class="icon"></div>
                                        Play Podcast
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="my-podcast">
                <a href="/register">Be a Guest on My Podcast! <i class="fas fa-arrow-right"></i> </a>
            </div>
        </div>
    </div>
</div>







<x-footer />