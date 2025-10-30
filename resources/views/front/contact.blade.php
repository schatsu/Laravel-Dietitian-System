@extends('front.layouts.base')
@section('page-title', 'İletişim')
@push('css')
    <style>
        .mt-lg-7 {
            margin-top: 5rem !important;
        }
    </style>
@endpush
@section('content')
    <!-- start section -->
    <section class="ipad-top-space-margin md-pt-0">
        <div class="container">
            <div class="row justify-content-center mb-3">
                <div class="col-lg-12 text-center appear anime-child anime-complete"
                     data-anime="{ &quot;el&quot;: &quot;childs&quot;, &quot;translateY&quot;: [30, 0], &quot;opacity&quot;: [0,1], &quot;duration&quot;: 600, &quot;delay&quot;: 0, &quot;staggervalue&quot;: 300, &quot;easing&quot;: &quot;easeOutQuad&quot; }">
                    <h2 class="text-dark-gray ls-minus-1px">İletişim</h2>
                    <div class="mt-auto justify-content-start breadcrumb breadcrumb-style-01 fs-14 text-dark-gray">
                        <ul>
                            <li><a href="{{route('home')}}"
                                   class="text-dark-gray text-dark-gray-hover">Ana sayfa</a></li>
                            <li><a href="{{route('contact')}}"
                                   class="text-dark-gray fw-bold text-dark-gray-hover">İletişim</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @if(collect($generalSetting)->filter()->isNotEmpty())
                <div class="row row-cols-1 row-cols-md-3 row-cols-sm-2 mt-4 justify-content-center"
                     data-anime='{ "el": "childs", "translateY": [0, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                    <div class="col sm-mb-30px text-center text-sm-start">
                        <span class="fs-14 text-uppercase fw-500 d-block">E-Posta</span>
                        <a href="mailto:{{$generalSetting?->site_email ?: ''}}"
                           class="fs-22 ls-minus-05px text-dark-gray fw-500 btn btn-link-gradient expand text-transform-none">{{$generalSetting?->site_email ?: ''}}
                            <span class="bg-dark-gray"></span></a>
                    </div>
                    <div class="col sm-mb-30px text-center text-sm-start">
                        <span class="fs-14 text-uppercase fw-500 d-block">Telefon</span>
                        <a href="tel:+{{$generalSetting?->phoneFormatted() ?: ''}}"
                           class="fs-22 ls-minus-05px text-dark-gray fw-500 btn btn-link-gradient expand text-transform-none">+{{$generalSetting?->phoneFormatted() ?: ''}}
                            <span class="bg-dark-gray"></span></a>
                    </div>
                    <div class="col text-center text-sm-start">
                        <span class="fs-14 text-uppercase fw-500 d-block">Diyetisyen</span>
                        <a @safeBlank href="https://maps.app.goo.gl/dS1yiJACJh8sHGBm9"
                           class="fs-22 ls-minus-05px text-dark-gray fw-500 btn btn-link-gradient expand text-transform-none">{{$generalSetting?->site_name ?: ''}}
                            <span class="bg-dark-gray"></span></a>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- end section -->
    <!-- start section -->
    @if(!empty($generalSetting->address))
        <section class="p-0 h-500px sm-h-350px overlap-height" id="location">
            <div class="container-fluid h-100 overlap-gap-section">
                <div class="row justify-content-center h-100">
                    <div class="col-12 p-0">
                        <div id="map" class="map h-500px md-h-400px sm-h-350px"
                             data-map-options='{ "lat": {{$generalSetting?->latitude ?: ''}}, "lng": {{$generalSetting?->longitude ?: ''}}, "style": "standart", "marker": { "type": "HTML", "color": "#005153" }, "popup": { "defaultOpen": true, "html": "<div class=infowindow><strong class=\"mb-3 d-inline-block alt-font\">{{$generalSetting?->site_name}}</strong><p class=\"alt-font\">{{$generalSetting?->address ?: ''}}</p></div><div class=\"google-maps-link alt-font\"> <a aria-label=\"Daha büyük haritayı görüntüle\" target=\"_blank\" jstcache=\"31\" href=\"https://maps.app.goo.gl/dS1yiJACJh8sHGBm9\" jsaction=\"mouseup:placeCard.largerMap\">Daha büyük haritayı görüntüle</a></div>" } }'></div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- end section -->
    <!-- start section -->
    <section class="@if(empty($generalSetting->address)) mt-lg-5 @endif">
        <div class="container overlap-section overlap-section-three-fourth "
             data-anime='{"el": "childs", "translateY": [50, 0], "opacity": [0,1], "duration": 800, "delay": 500, "staggervalue": 150, "easing": "easeOutQuad" }'>
            <div class="row row-cols-md-1 justify-content-center">
                <div class="col-xl-10">
                    <div class="bg-white p-8 border-radius-6px box-shadow-double-large">
                        <div class="row">
                            <div class="col-9">
                                <h3 class="alt-font text-dark-gray fw-700 ls-minus-2px mb-50px xs-mb-35px">
                                    <span
                                        data-fancy-text='{ "effect": "rotate", "string": ["İletişim", "Contact"] }'></span>
                                </h3>
                            </div>
                            <div class="col-3 text-end"
                                 data-anime='{ "translateY": [30, 0], "translateX": [-30, 0], "opacity": [0,1], "duration": 600, "delay": 300, "staggervalue": 300, "easing": "easeOutQuad" }'>
                                <i class="bi bi-send icon-large text-dark-gray animation-zoom"></i>
                            </div>
                        </div>
                        <!-- start contact form -->
                        <form action="{{route('submit-message')}}" method="post" class="row contact-form-style-02">
                            @csrf
                            <div class="col-md-12 mb-30px">
                                <input class="input-name form-control required" type="text" name="name"
                                       placeholder="Adınız*"/>
                            </div>
                            <div class="col-md-6 mb-30px">
                                <input class="form-control required" type="email" name="email"
                                       placeholder="E-posta Adresiniz*"/>
                            </div>
                            <div class="col-md-6 mb-30px">
                                <input class="form-control required" type="tel" name="phone"
                                       placeholder="Telefon Numaranız*"/>
                            </div>
                            <div class="col-md-12 mb-30px">
                                <textarea class="form-control required" cols="40" rows="4" name="message"
                                          placeholder="Mesajınız*"></textarea>
                            </div>
                            <div class="col-xl-7 col-md-7 last-paragraph-no-margin">
                                {{--                                <p class="text-center text-md-start fs-15 lh-26">--}}
                                {{--                                    We are committed to protecting your--}}
                                {{--                                    privacy. We will never collect information about you without your explicit--}}
                                {{--                                    consent.--}}
                                {{--                                </p>--}}
                            </div>
                            <div class="col-xl-5 col-md-5 text-center text-md-end sm-mt-20px">
                                <button class="btn btn-base-color btn-medium btn-rounded btn-box-shadow submit"
                                        type="submit">Mesajı Gönder</button>
                            </div>
                        </form>
                        <!-- end contact form -->
                    </div>
                </div>
                @if(collect($socialMediaSetting)->filter()->isNotEmpty())
                    <div class="row align-items-center justify-content-center mt-8">
                        <div class="col-md-auto text-center text-md-end sm-mb-20px">
                            <h3 class="text-dark-gray fw-600 ls-minus-1px">
                            <span
                                data-fancy-text='{ "effect": "rotate", "string": ["Sosyal medyada bağlantı kurun", "Connect with social media"] }'></span>
                            </h3>
                        </div>
                        <div class="col-12 d-none d-lg-inline-block">
                            <span class="w-100 h-1px bg-dark-gray opacity-2 d-flex mx-auto"></span>
                        </div>
                        <!-- start social icon -->
                        <div class="col-12 text-center elements-social social-icon-style-04">
                            <ul class="extra-large-icon dark">
                                @if(!empty($socialMediaSetting?->instagram))
                                    <li>
                                        <a class="instagram" href="{{ $socialMediaSetting->instagram }}" @safeBlank>
                                            <i class="fa-brands fa-instagram"></i><span></span>
                                        </a>
                                    </li>
                                @endif

                                @if(!empty($socialMediaSetting?->facebook))
                                    <li>
                                        <a class="facebook" href="{{ $socialMediaSetting->facebook }}" @safeBlank>
                                            <i class="fa-brands fa-facebook-f"></i><span></span>
                                        </a>
                                    </li>
                                @endif

                                @if(!empty($socialMediaSetting?->x))
                                    <li>
                                        <a class="twitter" href="{{ $socialMediaSetting->x }}" @safeBlank>
                                            <i class="fa-brands fa-x-twitter"></i><span></span>
                                        </a>
                                    </li>
                                @endif

                                @if(!empty($socialMediaSetting?->linkedin))
                                    <li>
                                        <a class="linkedin" href="{{ $socialMediaSetting->linkedin }}" @safeBlank>
                                            <i class="fa-brands fa-linkedin-in"></i><span></span>
                                        </a>
                                    </li>
                                @endif

                                @if(!empty($socialMediaSetting?->g_plus))
                                    <li>
                                        <a class="google" href="{{ $socialMediaSetting->g_plus }}" @safeBlank>
                                            <i class="fa-brands fa-google-plus-g"></i><span></span>
                                        </a>
                                    </li>
                                @endif

                                @if(!empty($socialMediaSetting?->youtube))
                                    <li>
                                        <a class="youtube" href="{{ $socialMediaSetting->youtube }}" @safeBlank>
                                            <i class="fa-brands fa-youtube"></i><span></span>
                                        </a>
                                    </li>
                                @endif

                                @if(!empty($socialMediaSetting?->vimeo))
                                    <li>
                                        <a class="vimeo" href="{{ $socialMediaSetting->vimeo }}" @safeBlank>
                                            <i class="fa-brands fa-vimeo"></i><span></span>
                                        </a>
                                    </li>
                                @endif

                                @if(!empty($socialMediaSetting?->email))
                                    <li>
                                        <a class="email" href="mailto:{{ $socialMediaSetting->email }}">
                                            <i class="fa-solid fa-envelope"></i><span></span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <!-- end social icon -->
                    </div>
                @endif
            </div>
        </div>
    </section>
    <!-- end section -->
@endsection
@push('script')
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key={{config('services.google.maps_api_key')}}&callback=initMap"></script>
@endpush
