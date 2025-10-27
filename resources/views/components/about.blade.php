<section>
    <div class="container">
        <div class="row justify-content-center align-items-center mb-6 sm-pb-9">
            <div class="col-lg-6 col-md-9 position-relative md-mb-15 text-center text-lg-start"
                 data-anime='{ "el": "childs", "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 15, "easing": "easeOutQuad" }'>
                <div class="absolute-middle-center z-index-9 counter-style-02 text-center">
                            <span
                                class="fs-160 fw-800 text-dark-gray ls-minus-10px xs-ls-minus-5px position-relative lg-fs-130 xs-fs-75">5<sub
                                    class="align-top fs-80 lg-fs-70 text-dark-gray position-relative top-minus-3px">+</sub></span>
                    <span class="d-block mx-auto fs-20 fw-500 lh-26 w-70 text-center text-dark-gray xs-w-100">Fazla Çalışma Deneyimi</span>
                </div>
                <img src="{{asset('front/images/demo-corporate-03.png')}}" alt="">
                <img src="https://craftohtml.themezaa.com/images/demo-corporate-01.png"
                     class="position-absolute top-50 left-minus-100px lg-left-minus-40px sm-left-minus-30px lg-w-50 sm-w-55"
                     data-bottom-top="transform: translateY(50px)"
                     data-top-bottom="transform: translateY(-220px)" alt="">
            </div>
            <div class="col-lg-6 ps-6 text-center text-lg-start lg-ps-15px"
                 data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                        <span
                            class="ps-25px pe-25px mb-20px text-uppercase text-base-color fs-14 lh-42px fw-700 border-radius-100px bg-gradient-very-light-gray-transparent d-inline-block">Hakkımda</span>
                <h3 class="text-dark-gray fw-700 ls-minus-1px">{{$about?->title}}</h3>
                <p class="w-80 xl-w-90 lg-w-100 mb-40px sm-mb-25px">{!! $about?->medium_about ?? '' !!}</p>
                <a href="{{route('about')}}"
                   class="btn btn-large btn-dark-gray btn-hover-animation-switch btn-box-shadow btn-rounded me-25px xs-me-0">
                                <span>
                                    <span class="btn-text">Okumaya Devam Edin...</span>
                                    <span class="btn-icon"><i class="feather icon-feather-arrow-right"></i></span>
                                    <span class="btn-icon"><i class="feather icon-feather-arrow-right"></i></span>
                                </span>
                </a>
                <span class="text-dark-gray fw-700 ls-minus-05px d-block d-sm-inline-block sm-mt-15px"><a
                        href="tel:1800222000"><i class="feather icon-feather-phone-call me-10px"></i>539 641 8747</a></span>
            </div>
        </div>
        <div
            class="row row-cols-1 row-cols-md-4 row-cols-sm-2 justify-content-center counter-style-07 ps-3 pe-3"
            data-anime='{ "el": "childs", "translateY": [50, 0], "opacity": [0,1], "duration": 1200, "delay": 0, "staggervalue": 150, "easing": "easeOutQuad" }'>

            <!-- start counter item -->
            <div class="col text-center sm-mb-30px">
                <h2 class="vertical-counter d-inline-flex text-dark-gray fw-800 mb-0 ls-minus-3px position-relative z-index-0"
                    data-to="1250">
            <span class="text-highlight position-absolute bottom-9px w-100">
                <span class="bg-gradient-flamingo-amethyst-green h-10px opacity-2"></span>
            </span>
                </h2>
                <span class="d-block fs-14 fw-700 text-uppercase text-dark-gray">Mutlu Danışan</span>
            </div>
            <!-- end counter item -->

            <!-- start counter item -->
            <div class="col text-center sm-mb-30px">
                <h2 class="vertical-counter d-inline-flex text-dark-gray fw-800 mb-0 ls-minus-3px position-relative z-index-0"
                    data-to="5">
            <span class="text-highlight position-absolute bottom-9px w-100">
                <span class="bg-gradient-flamingo-amethyst-green h-10px opacity-2"></span>
            </span>
                </h2>
                <span class="d-block fs-14 fw-700 text-uppercase text-dark-gray">Yıllık Tecrübe</span>
            </div>
            <!-- end counter item -->

            <!-- start counter item -->
            <div class="col text-center sm-mb-30px">
                <h2 class="vertical-counter d-inline-flex text-dark-gray fw-800 mb-0 ls-minus-3px position-relative z-index-0"
                    data-to="312">
            <span class="text-highlight position-absolute bottom-9px w-100">
                <span class="bg-gradient-flamingo-amethyst-green h-10px opacity-2"></span>
            </span>
                </h2>
                <span class="d-block fs-14 fw-700 text-uppercase text-dark-gray">Hazırlanan Diyet Listesi</span>
            </div>
            <!-- end counter item -->

            <!-- start counter item -->
            <div class="col text-center">
                <h2 class="vertical-counter d-inline-flex text-dark-gray fw-800 mb-0 ls-minus-3px position-relative z-index-0"
                    data-to="98">
            <span class="text-highlight position-absolute bottom-9px w-100">
                <span class="bg-gradient-flamingo-amethyst-green h-10px opacity-2"></span>
            </span>
                </h2>
                <span class="d-block fs-14 fw-700 text-uppercase text-dark-gray">Başarı Oranı (%)</span>
            </div>
            <!-- end counter item -->

        </div>

    </div>
</section>
