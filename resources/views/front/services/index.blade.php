@extends('front.layouts.base')
@section('page-title', 'Hizmetlerim')
@section('content')
    <section class="ipad-top-space-margin md-pt-0">
        <div class="container">
            <div class="row justify-content-center mb-3">
                <div class="col-lg-12 text-center appear anime-child anime-complete"
                     data-anime="{ &quot;el&quot;: &quot;childs&quot;, &quot;translateY&quot;: [30, 0], &quot;opacity&quot;: [0,1], &quot;duration&quot;: 600, &quot;delay&quot;: 0, &quot;staggervalue&quot;: 300, &quot;easing&quot;: &quot;easeOutQuad&quot; }">
                    <h2 class="text-dark-gray  ls-minus-1px">Hizmetlerim</h2>
                    <div class="mt-auto justify-content-start breadcrumb breadcrumb-style-01 fs-14 text-dark-gray">
                        <ul>
                            <li><a href="{{route('home')}}"
                                   class="text-dark-gray text-dark-gray-hover">Ana sayfa</a></li>
                            <li><a href="{{route('services.index')}}"
                                   class="text-dark-gray fw-bold text-dark-gray-hover">Hizmetlerim</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-lg-2 mb-2">
                <!-- start interactive banner item -->
                @foreach($services as $service)
                    <div class="col interactive-banner-style-08 md-mb-30px mb-lg-4">
                        <figure class="m-0 hover-box overflow-hidden position-relative border-radius-6px">
                            <img src="https://placehold.co/1160x640" alt="{{$service?->name}}"/>
                            <figcaption class="d-flex flex-column align-items-start justify-content-center position-absolute left-0px top-0px w-100 h-100 z-index-1 p-50px sm-p-6">
                                <div class="d-flex w-100 align-items-center mt-auto">
                                    <div class="col last-paragraph-no-margin pe-15px">
                                        <h5 class="alt-font text-white mb-0 fw-500">{{$service?->name ?? ''}}</h5>
                                        <p class="lh-38 text-white fw-300 ls-05px opacity-6 mb-0">{{$service?->description ?? ''}}</p>
                                    </div>
                                    <span class="border border-2 border-color-transparent-white-very-light bg-transparent w-60px h-60px sm-w-50px sm-h-50px rounded-circle ms-auto position-relative">
                                        <i class="bi bi-arrow-right-short absolute-middle-center icon-very-medium lh-0px text-white"></i>
                                    </span>
                                </div>
                                <div class="position-absolute left-0px top-0px w-100 h-100 bg-gradient-gray-light-dark-transparent z-index-minus-1 opacity-9"></div>
                                <a href="{{route('services.show', ['service' => $service?->slug])}}" class="position-absolute z-index-1 top-0px left-0px h-100 w-100"></a>
                            </figcaption>
                        </figure>
                    </div>
                @endforeach
                <!-- end interactive banner item -->
            </div>
            <div class="col-12 d-flex justify-content-center mb-3">
                <ul class="pagination pagination-style-01 fs-13 fw-500 mb-0">
                    {{$services->links('vendor.pagination.custom')}}
                </ul>
            </div>
        </div>
    </section>
@endsection


{{--<!-- start section -->--}}
{{--<section id="down-section" class="position-relative pb-4 mt-2 overflow-hidden">--}}
{{--    <div class="container">--}}
{{--        <div class="row align-items-center mb-8">--}}
{{--            <!-- Sol kısım: İstatistik kutuları -->--}}
{{--            <div class="col-lg-5 md-mb-50px" data-anime='{ "translateY": [0, 0], "perspective": [1200,1200], "scale": [1.1, 1], "rotateX": [50, 0], "opacity": [0,0.7], "duration": 800, "delay": 0, "staggervalue": 300, "easing": "easeOutQuad" }'>--}}
{{--                <div class="d-flex flex-column box-shadow-double-large border-radius-8px overflow-hidden">--}}
{{--                    <div class="row row-cols-1 justify-content-center m-0">--}}
{{--                        <div class="col p-9 border-bottom border-color-extra-medium-gray bg-white last-paragraph-no-margin">--}}
{{--                            <div class="d-flex align-items-center justify-content-center text-dark-gray">--}}
{{--                                <div class="flex-shrink-0 me-20px">--}}
{{--                                    <h2 class="mb-0 fs-60 fw-700 ls-minus-2px">98<sup class="fs-26">%</sup></h2>--}}
{{--                                </div>--}}
{{--                                <div>--}}
{{--                                    <span class="fs-18 lh-28 fw-600 d-block">Hedefine ulaşan<br/>danışan oranı</span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col p-9 border-bottom border-color-extra-medium-gray bg-white last-paragraph-no-margin">--}}
{{--                            <div class="d-flex align-items-center justify-content-center text-dark-gray">--}}
{{--                                <div class="flex-shrink-0 me-20px">--}}
{{--                                    <h2 class="mb-0 fs-60 fw-700 ls-minus-4px">4.9</h2>--}}
{{--                                </div>--}}
{{--                                <div>--}}
{{--                                    <div class="review-star-icon fs-22 d-inline-block text-gradient-orange-sky-blue">--}}
{{--                                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>--}}
{{--                                    </div>--}}
{{--                                    <span class="fs-18 lh-28 fw-600 d-block">Danışan memnuniyet puanı</span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col p-9 bg-white last-paragraph-no-margin">--}}
{{--                            <div class="d-flex align-items-center justify-content-center text-dark-gray">--}}
{{--                                <div class="flex-shrink-0 me-20px">--}}
{{--                                    <h2 class="mb-0 fs-60 fw-700 ls-minus-2px">92<sup class="fs-26">%</sup></h2>--}}
{{--                                </div>--}}
{{--                                <div>--}}
{{--                                    <span class="fs-18 lh-28 fw-600 d-block">Tekrar randevu <br/>alan danışanlar</span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <!-- Sağ kısım: Açıklama -->--}}
{{--            <div class="col-xl-5 offset-lg-1 col-lg-6" data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay":0, "staggervalue": 300, "easing": "easeOutQuad" }'>--}}
{{--                <span class="ps-25px pe-25px mb-20px text-uppercase text-base-color fs-14 lh-42px fw-700 border-radius-100px bg-gradient-very-light-gray-transparent d-inline-block">Profesyonel yaklaşım</span>--}}
{{--                <h3 class="text-dark-gray fw-700 ls-minus-1px">Sağlıklı yaşam hedeflerinize rekor sürede ulaşın.</h3>--}}
{{--                <p class="w-95 lg-w-100 mb-35px">Kişiye özel beslenme programlarımız, bilimsel veriler ve deneyimle hazırlanır. Amacımız; sürdürülebilir, sağlıklı ve keyifli bir yaşam tarzını size kazandırmak.</p>--}}
{{--                <div class="feature-box feature-box-left-icon bg-gradient-flamingo-amethyst-green border-radius-4px w-95 xl-w-100 mb-40px ps-30px pe-30px pt-15px pb-15px xs-p-15px sm-mb-30px xs-lh-normal">--}}
{{--                    <div class="feature-box-icon me-10px">--}}
{{--                        <img src="{{asset('front/images/bg-3.webp')}}" class="h-30px" alt="">--}}
{{--                    </div>--}}
{{--                    <div class="feature-box-content">--}}
{{--                        <span class="alt-font text-white fw-500">En iyi beslenme danışmanlık hizmeti.</span>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="row align-items-center text-center text-sm-start">--}}
{{--                    <div class="col-sm-auto xs-mb-5px">--}}
{{--                        <h3 class="alt-font text-dark-gray mb-0 d-inline-block align-middle me-10px lg-fs-32 fw-700">722+</h3>--}}
{{--                        <div class="text-center bg-dark-gray text-warning fs-14 border-radius-4px d-inline-block ps-15px pe-15px lh-34 align-middle me-5px">--}}
{{--                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-sm border-start border-2 border-color-dark-gray ps-25px ms-10px xl-ps-20px lg-ms-5px xs-border-start-0 xs-ps-15px xs-pe-15px xs-m-0">--}}
{{--                        <p class="m-0 lh-26 text-dark-gray fw-600">Mutlu danışanlarımızdan 5 yıldızlı yorumlar.</p>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <img src="{{asset('front/images/bg2.webp')}}" class="position-absolute top-80px left-170px opacity-7 z-index-minus-1" alt="Beslenme görseli"/>--}}
{{--</section>--}}
{{--<!-- end section -->--}}
{{--<!-- start section -->--}}
{{--<section class="bg-gradient-quartz-white position-relative z-index-0 border-radius-6px lg-border-radius-0px overflow-hidden">--}}
{{--    <div class="container">--}}
{{--        <div class="row justify-content-center mb-3">--}}
{{--            <div class="col-xl-6 col-lg-7 text-center"--}}
{{--                 data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay":0, "staggervalue": 300, "easing": "easeOutQuad" }'>--}}
{{--                <span class="ps-25px pe-25px mb-10px text-uppercase text-base-color fs-14 lh-42px fw-700 border-radius-100px bg-gradient-quartz-light-transparent d-inline-block">--}}
{{--                    Sağlıklı Yaşam Yolculuğu--}}
{{--                </span>--}}
{{--                <h3 class="text-dark-gray fw-700 ls-minus-1px">Hizmetlerim</h3>--}}
{{--                <p class="fs-16 text-light-opacity mt-10px">--}}
{{--                    Size özel beslenme programları ve sağlıklı yaşam rehberliği ile hedeflerinize ulaşmanız için buradayız.--}}
{{--                </p>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="row row-cols-1 row-cols-xl-3 row-cols-lg-3 row-cols-md-2 row-cols-sm-1 justify-content-center mb-5 transition-inner-all"--}}
{{--             data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay":0, "staggervalue": 300, "easing": "easeOutQuad" }'>--}}

{{--            @forelse($services as $service)--}}
{{--                <!-- start service item -->--}}
{{--                <div class="col interactive-banner-style-02 hover-box dark-hover md-mb-30px mb-lg-4">--}}
{{--                    <div class="h-100 text-center position-relative border-radius-6px box-shadow-quadruple-large overflow-hidden">--}}
{{--                        <figure class="m-0">--}}
{{--                            <a href="{{ route('services.show', $service->slug) }}" class="position-relative d-block">--}}
{{--                                <img src="{{$service?->getFirstMediaUrl('images', 'small') ?: 'https://placehold.co/600x400'}}" alt="{{ $service->name }}" />--}}
{{--                            </a>--}}
{{--                            <figcaption class="w-100 position-absolute bottom-0px bg-white">--}}
{{--                                <div class="position-relative z-index-2 p-40px pt-25px pb-15px border-bottom border-dark-opacity">--}}
{{--                                    <div class="mb-17px"></div>--}}
{{--                                    <a href="{{ route('services.show', $service->slug) }}"--}}
{{--                                       class="fw-600 d-inline-block mb-5px text-dark-gray fs-18">--}}
{{--                                        {{ $service->name }}--}}
{{--                                    </a>--}}
{{--                                    <p class="w-80 lg-w-100 fs-16 mx-auto mb-15px lg-mb-10px text-light-opacity">--}}
{{--                                        {{ $service->description }}--}}
{{--                                    </p>--}}
{{--                                </div>--}}
{{--                                <a href="{{ route('services.show', $service->slug) }}"--}}
{{--                                   class="btn btn-link btn-hover-animation-switch btn-small fw-700 text-dark-gray text-uppercase p-20px z-index-1">--}}
{{--                                    <span>--}}
{{--                                        <span class="btn-text">Detaylı İncele</span>--}}
{{--                                        <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>--}}
{{--                                        <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>--}}
{{--                                    </span>--}}
{{--                                </a>--}}
{{--                                <div class="box-overlay bg-base-color"></div>--}}
{{--                            </figcaption>--}}
{{--                        </figure>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <!-- end service item -->--}}
{{--            @empty--}}
{{--                <p class="text-center">Henüz hizmet eklenmemiş.</p>--}}
{{--            @endforelse--}}
{{--        </div>--}}

{{--        <div class="row justify-content-center g-0 mt-4">--}}
{{--            <div class="col-auto text-center last-paragraph-no-margin icon-with-text-style-08">--}}
{{--                <div class="feature-box feature-box-left-icon-middle overflow-hidden ps-30px pe-30px xs-ps-15px xs-pe-15px ">--}}
{{--                    <div class="feature-box-icon me-10px">--}}
{{--                        <i class="bi bi-chat-text fs-24 icon-very-medium text-base-color"></i>--}}
{{--                    </div>--}}
{{--                    <div class="feature-box-content last-paragraph-no-margin text-dark-gray text-uppercase fs-15 fw-700 ls-05px">--}}
{{--                        Sağlıklı bir yaşam için ilk adımı atalım.--}}
{{--                        <a href="#" class="text-base-color text-decoration-line-bottom-medium border-1">--}}
{{--                            Hemen Randevu Alın--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</section>--}}
{{--<!-- end section -->--}}



{{--<!-- start section -->--}}
{{--<section class="position-relative pt-0">--}}
{{--    <div class="container">--}}
{{--        <div class="row align-items-center">--}}
{{--            <!-- Görsel tarafı -->--}}
{{--            <div class="col-lg-6 position-relative md-mb-50px text-center text-md-center" data-anime='{ "opacity": [0,1], "duration": 1200, "delay":0, "staggervalue": 150, "easing": "easeOutQuad" }'>--}}
{{--                <img src="https://placehold.co/560x560?text=Sağlıklı+Beslenme" alt="Sağlıklı beslenme">--}}
{{--                <img src="https://placehold.co/144x64?text=Vitamin" class="position-absolute top-70px left-minus-20px lg-left-0px d-none d-sm-block" data-bottom-top="transform: translateY(-50px)" data-top-bottom="transform: translateY(50px)" alt="Vitamin kapsülleri">--}}
{{--                <img src="https://placehold.co/240x240?text=Sebze" class="position-absolute bottom-0px left-minus-60px lg-left-minus-30px xs-left-minus-10px xs-w-50" data-bottom-top="transform: translateY(50px)" data-top-bottom="transform: translateY(-50px)" alt="Sebze tabak">--}}
{{--                <img src="https://placehold.co/93x45?text=Su" class="position-absolute top-30 right-90px d-none d-sm-block" data-bottom-top="transform: translateY(-50px)" data-top-bottom="transform: translateY(50px)" alt="Su bardağı">--}}
{{--            </div>--}}

{{--            <!-- SSS tarafı -->--}}
{{--            <div class="col-xl-5 offset-xl-1 col-lg-6 text-center text-md-start" data-anime='{ "opacity": [0,1], "duration": 600, "delay":0, "staggervalue": 300, "easing": "easeOutQuad" }'>--}}
{{--                <span class="ps-25px pe-25px mb-20px text-uppercase text-base-color fs-14 lh-42px fw-700 border-radius-100px bg-gradient-very-light-gray-transparent d-inline-block">--}}
{{--                    Sıkça Sorulan Sorular--}}
{{--                </span>--}}
{{--                <h3 class="text-dark-gray fw-700 ls-minus-1px w-90 mb-50px md-mb-30px lg-w-100">--}}
{{--                    Diyet ve sağlıklı yaşam hakkında merak ettikleriniz--}}
{{--                </h3>--}}

{{--                <div class="accordion accordion-style-06 text-start" id="accordion-style-07">--}}
{{--                    <!-- start accordion item -->--}}
{{--                    <div class="accordion-item active-accordion">--}}
{{--                        <div class="accordion-header">--}}
{{--                            <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-07-01" aria-expanded="true" data-bs-parent="#accordion-style-07">--}}
{{--                                <div class="accordion-title fs-18 position-relative pe-0 alt-font text-dark-gray fw-600 mb-0">--}}
{{--                                    Kişiye özel diyet programı nedir?--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                        <div id="accordion-style-07-01" class="accordion-collapse collapse show" data-bs-parent="#accordion-style-07">--}}
{{--                            <div class="accordion-body last-paragraph-no-margin">--}}
{{--                                <p>Danışanın yaşam tarzı, sağlık durumu ve hedeflerine göre özel olarak hazırlanan beslenme planıdır.</p>--}}
{{--                                <i class="line-icon-Apple icon-extra-double-large opacity-2"></i>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- end accordion item -->--}}

{{--                    <!-- start accordion item -->--}}
{{--                    <div class="accordion-item">--}}
{{--                        <div class="accordion-header">--}}
{{--                            <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-07-02" aria-expanded="false" data-bs-parent="#accordion-style-07">--}}
{{--                                <div class="accordion-title fs-18 position-relative pe-0 alt-font text-dark-gray fw-600 mb-0">--}}
{{--                                    Diyet sürecinde aç kalır mıyım?--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                        <div id="accordion-style-07-02" class="accordion-collapse collapse" data-bs-parent="#accordion-style-07">--}}
{{--                            <div class="accordion-body last-paragraph-no-margin">--}}
{{--                                <p>Doğru planlanan diyetlerde açlık hissi minimuma iner, sağlıklı atıştırmalıklar ile enerji dengesi korunur.</p>--}}
{{--                                <i class="line-icon-Bread icon-extra-double-large opacity-2"></i>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- end accordion item -->--}}

{{--                    <!-- start accordion item -->--}}
{{--                    <div class="accordion-item">--}}
{{--                        <div class="accordion-header">--}}
{{--                            <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-07-03" aria-expanded="false" data-bs-parent="#accordion-style-07">--}}
{{--                                <div class="accordion-title fs-18 position-relative pe-0 alt-font text-dark-gray fw-600 mb-0">--}}
{{--                                    Hangi sıklıkla kontrol yapılır?--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                        <div id="accordion-style-07-03" class="accordion-collapse collapse" data-bs-parent="#accordion-style-07">--}}
{{--                            <div class="accordion-body last-paragraph-no-margin">--}}
{{--                                <p>Genellikle haftalık ya da iki haftada bir kontrol yapılır. İhtiyaca göre online görüşme desteği de verilir.</p>--}}
{{--                                <i class="line-icon-Calendar icon-extra-double-large opacity-2"></i>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- end accordion item -->--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</section>--}}
{{--<!-- end section -->--}}
