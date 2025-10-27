@extends('front.layouts.base')
@push('css')
    <style>
        .about-content ul li {
            list-style-type: disc !important;
        }
    </style>
@endpush
@section('content')
    <!-- start section -->
    <section id="down-section" class="border-bottom border-color-extra-medium-gray mt-2">
        <div class="container overlap-gap-section">
            <div class="row justify-content-center mb-3">
                <div class="col-lg-12 text-center appear anime-child anime-complete" data-anime="{ &quot;el&quot;: &quot;childs&quot;, &quot;translateY&quot;: [30, 0], &quot;opacity&quot;: [0,1], &quot;duration&quot;: 600, &quot;delay&quot;: 0, &quot;staggervalue&quot;: 300, &quot;easing&quot;: &quot;easeOutQuad&quot; }">
                    <h2 class="text-dark-gray ls-minus-1px">Hakkımda</h2>
                    <div class="mt-auto justify-content-start breadcrumb breadcrumb-style-01 fs-14 text-dark-gray">
                        <ul>
                            <li><a href="{{route('home')}}" class="text-dark-gray text-dark-gray-hover">Ana sayfa</a></li>
                            <li><a href="{{route('about')}}" class="text-dark-gray fw-bold text-dark-gray-hover">Hakkımda</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row align-items-center justify-content-center mb-5 md-mb-40px text-center text-md-start">
                <div class="about-content col-md-10 md-mb-20px text-center text-lg-start last-paragraph-no-margin">
                    <p class="w-90 xl-w-100" data-anime='{ "el": "lines", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 300, "easing": "easeOutQuad" }'>{!! str($about->content)->sanitizeHtml() !!}<span class="fw-600 text-dark-gray text-decoration-line-bottom"></span></p>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-lg-4 row-cols-md-2 row-cols-sm-2 justify-content-center" data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 150, "easing": "easeOutQuad" }'>
                <!-- start features box item -->
                <div class="col custom-icon-with-text-style-02">
                    <div class="feature-box p-6 last-paragraph-no-margin overflow-hidden md-mb-20px">
                        <div class="feature-box-icon">
                            <i class="fa-solid fa-graduation-cap fa-2xl mb-20px" style="color: #C4C1E9"></i>
                        </div>
                        <div class="feature-box-content">
                            <span class="d-block fs-19 fw-700 text-dark-gray mb-5px">Uzmanlık</span>
                            <p>Obezite Yönetimi</p>
                        </div>
                    </div>
                </div>
                <!-- end features box item -->
                <!-- start features box item -->
                <div class="col custom-icon-with-text-style-02">
                    <div class="feature-box p-6 last-paragraph-no-margin overflow-hidden md-mb-20px">
                        <div class="feature-box-icon">
                            <i class="fa-solid fa-language fa-2xl mb-20px" style="color: #C4C1E9"></i>
                        </div>
                        <div class="feature-box-content">
                            <span class="d-block fs-19 fw-700 text-dark-gray mb-5px">Diller</span>
                            <p>Türkçe, İnglizce</p>
                        </div>
                    </div>
                </div>
                <!-- end features box item -->
                <!-- start features box item -->
                <div class="col custom-icon-with-text-style-02">
                    <div class="feature-box p-6 last-paragraph-no-margin overflow-hidden xs-mb-20px">
                        <div class="feature-box-icon">
                            <i class="fa-solid fa-award fa-2xl mb-20px" style="color: #C4C1E9"></i>
                        </div>
                        <div class="feature-box-content">
                            <span class="d-block fs-19 fw-700 text-dark-gray mb-5px">Deneyim</span>
                            <p>5+ Yıl</p>
                        </div>
                    </div>
                </div>
                <!-- end features box item -->
                <!-- start features box item -->
                <div class="col custom-icon-with-text-style-02">
                    <div class="feature-box p-6 last-paragraph-no-margin overflow-hidden">
                        <div class="feature-box-icon">
                            <i class="fa-solid fa-users fa-2xl mb-20px" style="color: #C4C1E9"></i>
                        </div>
                        <div class="feature-box-content">
                            <span class="d-block fs-19 fw-700 text-dark-gray mb-5px">Danışan</span>
                            <p>1000 +</p>
                        </div>
                    </div>
                </div>
                <!-- end features box item -->
            </div>
        </div>
    </section>
    <!-- end section -->
    <section class="py-0 mb-20px sm-pt-50px appear anime-complete" data-anime="{ &quot;translateY&quot;: [0, 0], &quot;opacity&quot;: [0,1], &quot;duration&quot;: 1200, &quot;delay&quot;: 0, &quot;staggervalue&quot;: 150, &quot;easing&quot;: &quot;easeOutQuad&quot; }" style="">
        <div class="container overlap-section" style="margin-top: -37px;">
            <div class="row justify-content-center g-0">
                <div class="col-auto text-center last-paragraph-no-margin icon-with-text-style-08 pt-20px pb-20px ps-8 pe-8 md-ps-30px md-pe-30px bg-white border border-color-extra-medium-gray box-shadow-medium-bottom border-radius-100px xs-border-radius-10px">
                    <div class="feature-box feature-box-left-icon-middle overflow-hidden">
                        <div class="feature-box-icon me-10px">
                            <i class="bi bi-chat-text icon-extra-medium text-base-color"></i>
                        </div>
                        <div class="feature-box-content last-paragraph-no-margin text-dark-gray text-uppercase fs-15 fw-700 ls-05px">
                            Gelin birlikte harika bir iş çıkaralım. <a href="demo-corporate-contact.html" class="text-base-color text-decoration-line-bottom-medium border-1">Randevu Al</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <x-service/>
    <x-review/>
    <x-featured-blog/>
@endsection
