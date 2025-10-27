@if($sliders->isNotEmpty())
    <!-- start section -->
    <section class="p-0 bg-dark-gray">
        <div
            class="swiper lg-no-parallax full-screen md-h-600px sm-h-500px swiper-light-pagination ipad-top-space-margin magic-cursor drag-cursor"
            data-slider-options='{ "slidesPerView": 1, "loop": true, "parallax": true, "speed": 1200, "autoplay": { "delay": 4000, "disableOnInteraction": false }, "pagination": { "el": ".swiper-pagination-bullets", "clickable": true }, "navigation": { "nextEl": ".slider-one-slide-next-1", "prevEl": ".slider-one-slide-prev-1" }, "keyboard": { "enabled": true, "onlyInViewport": true }, "effect": "slide" }'>
            <div class="swiper-wrapper">
                <!-- start slider item -->
                @foreach($sliders as $slider)
                    <div class="swiper-slide overflow-hidden">
                        <div class="cover-background position-absolute top-0 start-0 w-100 h-100"
                             style="background-image:url('{{$slider?->getFirstMediaUrl('slider', 'slider') ?? 'https://placehold.co/1920x1080'}}');"
                             data-swiper-parallax="1000">
                            <div class="opacity-light bg-gradient-black-green"></div>
                            <div class="container h-100" data-swiper-parallax="-300">
                                <div class="row align-items-center justify-content-center h-100 text-center">
                                    <div class="col-xl-7 col-lg-9 col-md-10 position-relative text-white">
                                    <span
                                        class="opacity-7 fs-80 xs-fs-60 alt-font fw-700 text-shadow-extra-large ls-minus-2px mb-45px sm-mb-30px xs-mb-20px d-inline-block swiper-parallax-fancy-text"
                                        data-fancy-text='{ "effect": "rotate", "string": ["{{$slider?->title ?? ''}}"] }'></span>
                                        <div
                                            data-anime='{ "el": "childs", "translateY": [80, 0], "opacity": [0,1], "duration": 600, "delay": 1000, "staggervalue": 300, "easing": "easeOutQuad" }'>
                                            <a href="#"
                                               @safeBlank class="btn btn-large btn-transparent-white-light border-1 btn-hover-animation btn-box-shadow btn-round-edge xs-m-10px">
                                                <span>
                                                    <span class="btn-text">Randevu Al <i
                                                            class="fa-regular fa-calendar"></i></span>
                                                    <span class="btn-icon"><i
                                                            class="feather icon-feather-arrow-right"></i></span>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <!-- end slider item -->
            </div>
            <!-- start slider pagination -->
            <div class="swiper-pagination swiper-pagination-clickable swiper-pagination-bullets"></div>
            <!-- end slider pagination -->
        </div>
    </section>
    <!-- end section -->
    <!-- start section -->
    <section class="p-0 border-bottom border-color-extra-medium-gray">
        <div class="container">
            <div class="row row-cols-1 row-cols-md-3 row-cols-sm-2 justify-content-center"
                 data-anime='{ "el": "childs", "translateX": [50, 0], "opacity": [0,1], "duration": 800, "delay": 0, "staggervalue": 500, "easing": "easeOutQuad" }'>

                <!-- Danışan Başarı Oranı -->
                <div
                    class="col pt-35px pb-35px md-pb-0 text-dark-gray border-end border-color-extra-medium-gray sm-border-end-0">
                    <div
                        class="d-flex flex-column flex-lg-row align-items-center justify-content-center text-center text-lg-start">
                        <div class="flex-shrink-0 me-15px md-me-0">
                            <h2 class="mb-0 fw-800">98<sup class="fs-24">%</sup></h2>
                        </div>
                        <div>
                            <span class="lh-24 fw-600 d-block">Hedefine ulaşan <br/> danışan oranı</span>
                        </div>
                    </div>
                </div>

                <!-- Puan ve Yorumlar -->
                <div
                    class="col pt-35px pb-35px md-pb-0 text-dark-gray border-end border-color-extra-medium-gray sm-border-end-0">
                    <div
                        class="d-flex flex-column flex-lg-row align-items-center justify-content-center text-center text-lg-start">
                        <div class="flex-shrink-0 me-15px md-me-0">
                            <h2 class="mb-0 fw-800 ls-minus-3px">4.9</h2>
                        </div>
                        <div>
                            <div class="review-star-icon fs-20 d-inline-block text-gradient-orange-sky-blue">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i>
                            </div>
                            <span class="lh-24 fw-600 d-block">Danışan memnuniyet puanı</span>
                        </div>
                    </div>
                </div>

                <!-- Tekrar Randevu Oranı -->
                <div class="col pt-35px pb-35px text-dark-gray">
                    <div
                        class="d-flex flex-column flex-lg-row align-items-center justify-content-center text-center text-lg-start">
                        <div class="flex-shrink-0 me-15px md-me-0">
                            <h2 class="mb-0 fw-800">97<sup class="fs-24">%</sup></h2>
                        </div>
                        <div>
                            <span class="lh-24 fw-600 d-block">Tekrar randevu <br/> alan danışanlar</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- end section -->
@endif
