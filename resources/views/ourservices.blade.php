<x-header />

<div id="hero-sec" class="back-img">
    <div class="row">
        <div class="container our-services">
            <h2>{{ $ourservicespage->title }}</h2>
            <p>{{ $ourservicespage->description }}</p>
        </div>
    </div>
</div>


<div id="study-buddy-sec" class="study-buddy-set">
    <div class="row">
        <div class="container">
            <div class="column1"><img src="{{ asset('storage/'.$ourservicespage->service1_image) }}" alt=""></div>
            <div class="column2">
                <div class="number-box">
                <div><span class="number">1</span></div>    
                <div>
                <h3 class="title-study">{{ $ourservicespage->service1_title }}</h3>
                <p class="study-despction">{{ $ourservicespage->service1_description }}</p>
                </div>
                </div>
                <div class="how-set-title">
                        <h3>{{ $ourservicespage->service1_title2 }}</h3>
                    </div>
                    <div class="list-box">
                        @if(!empty($ourservicespage->service1_how_it_work))
                        <ul class="ul-list">
                            @foreach($ourservicespage->service1_how_it_work as $service1_work)
                                <li><span class="check-icon"><i class="fa-regular fa-circle-check"></i></span> <b>{{ $service1_work['name'] }}:</b> {{ $service1_work['description'] }}</li>
                            @endforeach
                        </ul>
                        @endif                        
                    </div>

                    <p class="study-despction">{{ $ourservicespage->service1_more_description }}</p>

                    <div class="hero-button our-button-set">
                        @if(!empty($ourservicespage->cta_button_service1))
                            @foreach($ourservicespage->cta_button_service1 as $index => $cta_button_service1)
                            @php
                                $newcls = ($index == 0) ? 'two-btn' : '';
                                $slug = ltrim($cta_button_service1['btn_url'], '/');
                                $url = ($index == 0) ? route('service.show', ['slug' => $slug]) : $cta_button_service1['btn_url'];
                            @endphp
                            <div class="btn {{$newcls}}"><a href="{{ $url }}" target='_blank'>{{ $cta_button_service1['btn_txt'] }} <i class="fas fa-arrow-right"></i></a></div>
                            @endforeach
                        @endif
                    </div>
            </div>
        </div>
    </div>
</div>


<div id="study-buddy-sec" class="tutor-arcade">
    <div class="row">
        <div class="container">
        <div class="column2">
                <div class="number-box">
                <div><span class="number">2</span></div>    
                <div>
                <h3 class="title-study">{{ $ourservicespage->service2_title }}</h3>
                <p class="study-despction">{{ $ourservicespage->service2_description }}</p>
                </div>
                </div>

                    <div class="how-set-title">
                        <h3>{{ $ourservicespage->service2_title2 }}</h3>
                    </div>

                    <div class="list-box">
                        @if(!empty($ourservicespage->service2_how_it_work))
                        <ul class="ul-list">
                            @foreach($ourservicespage->service2_how_it_work as $service2_work)
                            <li><span class="check-icon"><i class="fa-regular fa-circle-check"></i></span> <b>{{ $service2_work['name'] }}:</b> {{ $service2_work['description'] }}</li>
                            @endforeach
                        </ul> 
                        @endif                       
                    </div>

                    <p class="study-despction">{{ $ourservicespage->service2_more_description }}</p>

                    <div class="hero-button our-button-set">
                        @if(!empty($ourservicespage->cta_button_service2))
                            @foreach($ourservicespage->cta_button_service2 as $index => $cta_button_service2)
                            @php
                                $newcls = ($index == 0) ? 'two-btn' : '';
                                $slug = ltrim($cta_button_service2['btn_url'], '/');
                                $url = ($index == 0) ? route('service.show', ['slug' => $slug]) : $cta_button_service1['btn_url'];
                            @endphp
                            <div class="btn {{$newcls}}"><a href="{{ $url }}" target='_blank'>{{ $cta_button_service2['btn_txt'] }} <i class="fas fa-arrow-right"></i></a></div>
                            @endforeach
                        @endif
                    </div>
            </div>
            <div class="column1"><img src="{{ asset('storage/'.$ourservicespage->service2_image) }}" alt=""></div>
            
        </div>
    </div>
</div>




<x-footer />