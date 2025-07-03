<x-header />


<div id="hero-sec" class="back-img">
    <div class="row">
        <div class="container our-services">
            <h2>{{ $meetourteampage->title }}</h2>
            <p>{{ $meetourteampage->description }}</p>
        </div>
    </div>
</div>


<div id="study-buddy-sec" class="study-buddy-set">
    <div class="row">
            <h2 class="leaders-title">{{ $meetourteampage->section1_title }} </h2>
        <div class="container">
            <div class="column1"><img src="{{ asset('storage/'.$meetourteampage->leaders1_image) }}" alt="">
                
                <div class="list-box exam-box">
                <div class="how-set-title exma-title">
                    <h3 class="">{{ $meetourteampage->exam_score_title }}</h3>
                </div>
                    @if($meetourteampage->exam_score_info)
                        <ul class="ul-list">
                            @foreach($meetourteampage->exam_score_info as $exam_score_info)
                                <li> {{ $exam_score_info['detail'] }}</li>
                            @endforeach
                        </ul>    
                    @endif                    
                </div>
            </div>
            <div class="column2">
            
                    <div class="how-set-title">
                        <h3>{{ $meetourteampage->leaders1_name }}</h3>
                        <span class="sub-title">{{ $meetourteampage->leaders1_designation }}</span>
                        <p class="study-despction">{{ $meetourteampage->leaders1_description }}</p>
                    </div>


                    <div class="how-set-title">
                        <h3>{{ $meetourteampage->credential_title }}</h3>
                    </div>

                    <div class="list-box">
                        @if($meetourteampage->credential_info)
                            <ul class="ul-list credential-inf">
                                @foreach($meetourteampage->credential_info as $credential_info)
                                    <li><span class="check-icon"><i class="fa-solid fa-circle-dot"></i></span>{{ $credential_info['detail1'] }}</li>
                                    @if(!empty($credential_info['detail2']))
                                    <ul>
                                        <li><span class="check-icon"><i class="fa-solid fa-angle-right"></i></span>{{ $credential_info['detail2'] }}</li>
                                    </ul>
                                    
                                    @endif
                                @endforeach
                            </ul>    
                        @endif                    
                    </div>

                    <div class="hero-button our-button-set">
                        <div class="btn two-btn"><a href="#">Read More <i class="fas fa-arrow-right"></i></a></div>
                        <div class="btn"><a href="#"> Book a Free Consultation <i class="fas fa-arrow-right"></i></a></div> 
                    </div>
            </div>
        </div>
    </div>
</div>



<div id="alyssa">
    <div class="row">
        <div class="container">
        <div class="">
                <div class="how-set-title">
                    <h3 class="alyssa-set">{{ $meetourteampage->leaders2_name }}</h3>
                    <span class="sub-title">{{ $meetourteampage->leaders2_designation }}</span>
                    <p class="study-despction">{{ $meetourteampage->leaders2_description }}</p>
                </div>
        </div>
        <div class="">
                <img src="{{ asset('storage/'.$meetourteampage->leaders2_image) }}" alt="">
        </div>
        </div>
    </div>
</div>


<div class="Tutors-sec">
    <div class="Tutors-column">
        <h2>{{ $meetourteampage->section2_title }}</h2>
        <p>{{ $meetourteampage->section2_desc }}</p>
    </div>
        @if($meetourteampage->tutor_detail)
            <div class="team-card">
                @foreach($meetourteampage->tutor_detail as $tutor_detail)
                <div class="card-set">
                    <img src="{{ asset('storage/'.$tutor_detail['tutors_image']) }}" alt="">
                        <div class="card-info">
                            <h3>{{ $tutor_detail['tutors_name'] }}</h3>
                            <span>{{ $tutor_detail['tutors_designation'] }}</span>
                            <div class="about-button">
                                <a href="{{ $tutor_detail['cta_btn_url'] }}">{{ $tutor_detail['cta_btn_text'] }} <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                </div>
                @endforeach
            </div>
        @endif
</div>
<x-footer />