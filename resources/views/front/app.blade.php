@extends('front.layouts.base')
@section('page-title', 'Ana sayfa')
@section('content')
    <!-- start slider -->
    <x-slider/>
    <!-- end slider -->
    <!-- start about -->
    <x-about/>
    <!-- end about -->
    <!-- start services -->
    <x-service/>
    <!-- end services -->
    <!-- start reviews -->
    <x-review/>
    <!-- start reviews -->
    <!-- start featured blogs-->
    <x-featured-blog/>
    <!-- end featured blogs -->
    <!-- start section -->
    <section>
        <div class="container position-relative">
            <div class="row align-items-center mb-7">
                <div class="col-xxl-4 col-lg-5 md-mb-15 sm-mb-20 text-center text-lg-start">
                    <span class="ps-25px pe-25px mb-20px text-uppercase text-base-color fs-14 lh-42px fw-700 border-radius-100px bg-gradient-very-light-gray-transparent d-inline-block">Kolay süreç</span>
                    <h3 class="text-dark-gray fw-700 ls-minus-2px mb-40px">Sağlıklı yaşama adım adım.</h3>
                    <div class="row row-cols-1" data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                        <!-- start process step item -->
                        <div class="col-12 process-step-style-05 position-relative hover-box">
                            <div class="process-step-item d-flex">
                                <div class="process-step-icon-wrap position-relative">
                                    <div class="process-step-icon d-flex justify-content-center align-items-center mx-auto rounded-circle h-60px w-60px fs-14 bg-light-green fw-700 position-relative">
                                        <span class="number position-relative z-index-1 text-dark-gray">01</span>
                                        <div class="box-overlay bg-base-color rounded-circle"></div>
                                    </div>
                                    <span class="progress-step-separator bg-dark-gray opacity-1"></span>
                                </div>
                                <div class="process-content ps-30px last-paragraph-no-margin mb-30px">
                                    <span class="d-block fw-700 text-dark-gray mb-5px fs-18">İlk Görüşme & Analiz</span>
                                    <p class="w-90 lg-w-100 lh-32">Beden ölçümleri, kan değerleri ve yaşam tarzı analizi ile sürece başlıyoruz.</p>
                                </div>
                            </div>
                        </div>
                        <!-- end process step item -->

                        <!-- start process step item -->
                        <div class="col-12 process-step-style-05 position-relative hover-box">
                            <div class="process-step-item d-flex">
                                <div class="process-step-icon-wrap position-relative">
                                    <div class="process-step-icon d-flex justify-content-center align-items-center mx-auto rounded-circle h-60px w-60px fs-14 bg-light-green fw-700 position-relative">
                                        <span class="number position-relative z-index-1 text-dark-gray">02</span>
                                        <div class="box-overlay bg-base-color rounded-circle"></div>
                                    </div>
                                    <span class="progress-step-separator bg-dark-gray opacity-1"></span>
                                </div>
                                <div class="process-content ps-30px last-paragraph-no-margin mb-30px">
                                    <span class="d-block fw-700 text-dark-gray mb-5px fs-18">Kişisel Beslenme Planı</span>
                                    <p class="w-90 lg-w-100 lh-32">Size özel, dengeli ve sürdürülebilir bir diyet listesi hazırlanır.</p>
                                </div>
                            </div>
                        </div>
                        <!-- end process step item -->

                        <!-- start process step item -->
                        <div class="col-12 process-step-style-05 position-relative hover-box xs-mb-30px">
                            <div class="process-step-item d-flex">
                                <div class="process-step-icon-wrap position-relative">
                                    <div class="process-step-icon d-flex justify-content-center align-items-center mx-auto rounded-circle h-60px w-60px fs-14 bg-light-green fw-700 position-relative">
                                        <span class="number position-relative z-index-1 text-dark-gray">03</span>
                                        <div class="box-overlay bg-base-color rounded-circle"></div>
                                    </div>
                                </div>
                                <div class="process-content ps-30px last-paragraph-no-margin">
                                    <span class="d-block fw-700 text-dark-gray mb-5px fs-18">Takip & Motivasyon</span>
                                    <p class="w-90 lg-w-100 lh-32">Haftalık kontroller ve motivasyon desteği ile hedefinize ulaşmanız sağlanır.</p>
                                </div>
                            </div>
                        </div>
                        <!-- end process step item -->
                    </div>
                </div>

                <!-- Görsel Alan -->
                <div class="col-lg-7 offset-xxl-1 position-relative md-mb-30px sm-mb-15"
                     data-anime='{ "translateX": [0, 0], "opacity": [0,1], "duration": 1200, "delay": 0, "staggervalue": 150, "easing": "easeOutQuad" }'>
                    <img src="{{asset('front/images/asdf.jpg')}}" class="rounded position-relative z-index-9 top-40px" alt="">
                </div>
            </div>

            <!-- Alt CTA -->
            <div class="row justify-content-center align-items-center">
                <div class="col-12 text-center align-items-center" data-anime='{ "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                    <div class="bg-base-color fw-700 text-white text-uppercase border-radius-30px ps-20px pe-20px fs-12 me-10px sm-m-5px d-inline-block align-middle">Hadi Başlayalım</div>
                    <div class="fs-18 fw-500 text-dark-gray d-inline-block align-middle">
                        Sağlıklı yaşam yolculuğunuza bugün başlayın. <a href="{{route('contact')}}" class="text-dark-gray text-decoration-line-bottom fw-700">İletişime geçin</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end section -->
    <!-- start contact -->
    <x-contact-form/>
    <!-- end contact -->
@endsection
@push('script')
    <script async defer
                src="https://maps.googleapis.com/maps/api/js?key={{config('services.google.maps_api_key')}}&callback=initMap"></script>
@endpush
