<x-header />

<div id="hero-sec">
    <div class="row">
        <div class="container">
            <div class="column">
                <h3>{{ $homepage->hero_title }}</h3>
                <h2>{{ $homepage->hero_subtitle }}</h2>
                <p>{{ $homepage->hero_description }}</p>
                <div class="hero-button">
                    <div class="btn"><a href="{{ $homepage->cta_link1 }}">{{ $homepage->cta_text1 }} <i class="fas fa-arrow-right"></i></a></div>
                    <div class="btn two-btn"><a href="{{ $homepage->cta_link2 }}">{{ $homepage->cta_text2 }} <i class="fas fa-arrow-right"></i></a></div>
                </div>
            </div>
            <!-- <div class="column">
            <img src="{{ asset('storage/'.$homepage->hero_image) }}" alt="">
            </div> -->
        </div>
    </div>
</div>

<div id="review-sec">
    <div class="row">
        <div class="container">
            @if(!empty($homepage->statistics))
                @foreach($homepage->statistics as $statistic)
                <div class="column">
                    <img src="{{ asset('storage/'.$statistic['icon_image']) }}" alt="">
                    <h5>{{ $statistic['number'] }}</h5>
                    <p>{{ $statistic['label'] }}</p>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<div id="about-sec">
    <div class="row">
        <div class="container">
            <div class="column">
                <div class="review-box">
                <div><img src="{{ asset('img/icon-1.png') }}" alt=""></div>
                <div>
                    <h5>50K+</h5>
                    <div class="rewi-txt">Positive Review</div>
                </div>
                
                </div>
                <img class="full-img" src="{{ asset('storage/'.$homepage->about_image) }}" alt="">
            </div>
            <div class="column">
                <video width="439" height="582" crossorigin="anonymous" playsinline="" preload="auto" muted="" loop="" tabindex="-1" autoplay="">
                    <source src="{{ asset('storage/'.$homepage->about_video) }}" type="video/mp4">
                </video>
                <!-- <img src="{{ asset('storage/'.$homepage->about_image) }}" alt=""> -->
            </div>
            <div class="column left-sp">
                <h2>{{ $homepage->about_title }}</h2>
                <p class="about-despction">{{ $homepage->about_description }}</p>
                <div class="tabs-container">
                    <!-- Tab Buttons -->
                    <div class="tabs">
                        <button class="tab-button active" data-tab="mission-values">Mission And Values</button>
                        <button class="tab-button btn-two" data-tab="educational-approach">Educational Approach</button>
                    </div>

                    <!-- Tab Content -->
                    <div id="mission-values" class="tab-content active">
                        @if(!empty($homepage->mission_and_values))
                            <ul>
                                @foreach($homepage->mission_and_values as $mission_and_values)
                                    <li>{{ $mission_and_values['values_of_mission'] }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div id="educational-approach" class="tab-content">
                        @if(!empty($homepage->educational_approach))
                            <ul>
                                @foreach($homepage->educational_approach as $educational_approach)
                                    <li>{{ $educational_approach['education_approach'] }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div class="team-section">
                        @if(!empty($homepage->founder_data))
                            @foreach($homepage->founder_data as $founder_data)
                                <div class="team-member">
                                    <div class="member-set">
                                        <div> <img src="{{ asset('storage/'.$founder_data['founder_image']) }}" alt=""></div>
                                        <div><h3>{{ $founder_data['founder_name'] }}</h3>
                                            <p>{{ $founder_data['founder_designation'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- About Us Button -->
                    <div class="about-button">
                        <a href="#about-us">About Us <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Video sec --->
 <div id="video-sec">
    <div class="row">
        <div class="container">
                <video width="1307" height="492" crossorigin="anonymous" playsinline="" preload="auto" muted="" loop="" tabindex="-1" autoplay="">
                    <source src="{{ asset('storage/'.$homepage->why_choose_us_video) }}" type="video/mp4">
                </video>

            <div class="Why-Choose-sec">
                <div class="Why-Choose-column">
                    <h4>{{ $homepage->why_title }}</h4>
                    <h2>{{ $homepage->why_subtitle }}</h2>
                    <img src="{{ asset('img/arrow.png') }}" alt="">
                </div>
                @if(!empty($homepage->why_choose_us_data))
                    @foreach($homepage->why_choose_us_data as $index => $why_choose_us_data)
                        <div class="Why-Choose-column">
                            <div class="study-box">
                                <span class="number">{{ $index + 1 }}</span>
                                <h3 class="title-study">{{ $why_choose_us_data['title'] }}</h3>
                                <p class="study-despction">{{ $why_choose_us_data['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
 </div>


<!-- Testimonial -->
<div id="Testimonial-sec">
    <div class="row">
        <div class="container">
            <h4>{{ $homepage->testimonial_title }}</h4>
            <h2>{{ $homepage->testimonial_subtitle }}</h2>

            <div class="testimonial-slider owl-carousel">
                @if(!empty($homepage->testimonial_data))
                    @foreach($homepage->testimonial_data as $testimonial_data)
                        <div class="testimonial">
                            @if($testimonial_data['star'] == 1)
                                <img src="{{ asset('img/1star.png') }}" alt="" class="review-star">
                            @elseif($testimonial_data['star'] == 2)
                                <img src="{{ asset('img/2star.png') }}" alt="" class="review-star">
                            @elseif($testimonial_data['star'] == 3)
                                <img src="{{ asset('img/3star.png') }}" alt="" class="review-star">
                            @elseif($testimonial_data['star'] == 4)
                                <img src="{{ asset('img/4star.png') }}" alt="" class="review-star">
                            @elseif($testimonial_data['star'] == 5)
                                <img src="{{ asset('img/review.png') }}" alt="" class="review-star">
                            @endif
                            <p>{{ $testimonial_data['description'] }}</p>
                            <div class="testimonial-author">
                                <img src="{{ asset('storage/'. $testimonial_data['image']) }}" alt="{{ $testimonial_data['name'] }}">
                                <h4>{{ $testimonial_data['name'] }}</h4>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>


<div id="expert-best">
    <div class="row">
        <div class="container">
            <div class="column">
                <h4>{{ $homepage->tutor_title }}</h4>
                <h2>{{ $homepage->tutor_subtitle }}</h2>
                <p class="about-despction">{{ $homepage->tutor_description }}</p>
                <div class="about-button">
                    <a href="{{ $homepage->tutor_button_link }}">{{ $homepage->tutor_button_text }} <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            @if(!empty($homepage->tutor_image))
                @foreach($homepage->tutor_image as $tutor_image)
                    <div class="column">
                        <div class="content-client">
                            <img src="{{ asset('storage/'. $tutor_image['image']) }}" alt="" class="client">
                            <div class="content-client-card">    
                                <h3>{{ $tutor_image['name'] }}</h3>
                                <p class="client-descption">{{ $tutor_image['description'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<!--contact--->
<div id="contact-sec">
    <div class="row">
        <div class="container">
            <h2>Contact Us </h2>

            <div class="form-container">
                <form class="contact-form" method="POST" action="{{ route('contact.store') }}">
                    @csrf
                    <div class="form-row">
                        <input type="text" name="first_name" placeholder="First Name" required>
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="tel" name="phone" placeholder="Phone Number" required>
                    </div>
                    <div class="form-row">
                    <textarea name="message" placeholder="Your Message" required> </textarea>
                    <button type="submit" class="submit-button">SUBMIT NOW <span><i class="fas fa-arrow-right"></i></span></button>   
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Video sec --->
<div id="Podcast">
    <div class="container">
            <!-- Podcast Info Section -->
            <div class="Podcast-column content-box-desp">
                <h4>{{ $homepage->podcast_title }}</h4>
                <h2>{{ $homepage->podcast_subtitle }}</h2>
                <p class="about-despction">{{ $homepage->podcast_description }}</p>
                <div class="about-button">
                    <a href="{{ $homepage->podcast_button_link }}">{{ $homepage->podcast_button_text }} <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <!-- Podcast Slider Section -->
            <div class="Podcast-column">
                <div class="slider-container">
                    @if(!empty($homepage->podcast_image))
                        <div class="owl-carousel">
                            <!-- Slide 1 -->
                            @foreach($homepage->podcast_image as $podcast_image)
                                <div class="slide">
                                    <img src="{{ asset('storage/'. $podcast_image['image']) }}" alt="Podcast 1">
                                    <h3>{{ $podcast_image['description'] }}</h3>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
    </div>
</div>




<div id="frequently-sec">
    <div class="row">
        <div class="container">
            <div class="faq-column">
                <h4>{{ $homepage->faq_title }}</h4>
                <h2>{{ $homepage->faq_subtitle }}</h2>
                @if(!empty($homepage->faq_list))
                    <div class="accordion">
                        <!-- Accordion Item 1 -->
                        @foreach($homepage->faq_list as $faq_list)
                            <div class="accordion-item">
                                <button class="accordion-header">
                                    {{ $faq_list['title'] }}
                                    <span class="accordion-icon"><i class="fa-solid fa-angle-right"></i></span>
                                </button>
                                <div class="accordion-content">
                                    <p>{{ $faq_list['description'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="faq-column">
            <img src="{{ asset('storage/'. $homepage->faq_image) }}" alt="Podcast 1">
            </div>
            
        </div>
    </div>
</div>









<x-footer />
