<!-- start section -->
<section class="pt-0">
    <div class="container-fluid p-0">
        <div class="row g-0"
             data-anime='{ "el": "childs", "translateX": [-50, 0], "opacity": [0,1], "duration": 800, "delay": 300, "staggervalue": 150, "easing": "easeOutQuad" }'>
            <div class="col-lg-8">
                <div id="map" class="map h-100 md-h-450px"
                     data-map-options='{ "lat": {{$generalSetting?->latitude ?: ''}}, "lng": {{$generalSetting?->longitude ?: ''}}, "style": "standart", "marker": { "type": "HTML", "color": "#005153" }, "popup": { "defaultOpen": true, "html": "<div class=infowindow><strong class=\"mb-3 d-inline-block alt-font\">{{$generalSetting?->site_name}}</strong><p class=\"alt-font\">{{$generalSetting?->address ?: ''}}</p></div><div class=\"google-maps-link alt-font\"> <a aria-label=\"Daha büyük haritayı görüntüle\" target=\"_blank\" jstcache=\"31\" href=\"https://maps.app.goo.gl/dS1yiJACJh8sHGBm9\" jsaction=\"mouseup:placeCard.largerMap\">Daha büyük haritayı görüntüle</a></div>" } }'></div>
            </div>
            <div class="col-xxl-4 col-lg-6 position-relative">
                <div
                    class="contact-form-style-03 position-relative bg-spring-wood z-index-1 p-14 lg-p-10 sm-p-30px overflow-hidden last-paragraph-no-margin">
                    <h3 class="text-dark-gray mb-30px sm-mb-20px fancy-text-style-4 ls-minus-2px">
                        <span data-fancy-text='{ "effect": "rotate", "string": ["Merhaba!", "Hello!"] }'></span>
                    </h3>
                    <form action="{{route('submit-message')}}" method="post">
                        @csrf
                        <div class="position-relative form-group mb-20px">
                            <input
                                class="ps-0 border-radius-0px medium-gray bg-transparent border-color-light-red form-control required"
                                type="text" name="name" placeholder="Adınız*"/>
                        </div>
                        <div class="position-relative form-group mb-20px">
                            <input
                                class="ps-0 border-radius-0px medium-gray bg-transparent border-color-light-red form-control required"
                                type="email" name="email" placeholder="E-Posta Adresiniz*"/>
                        </div>

                        <div class="position-relative form-group mb-20px">
                            <input
                                class="ps-0 border-radius-0px medium-gray bg-transparent border-color-light-red form-control required"
                                type="tel" name="phone" placeholder="Telefon Numaranız*"/>
                        </div>

                        <div class="position-relative z-index-1 form-group form-textarea mt-15px mb-0">
                                <textarea
                                    class="ps-0 border-radius-0px medium-gray bg-transparent border-color-light-red form-control required"
                                    name="message" placeholder="Mesajınız" rows="3"></textarea>
                            <button
                                class="btn btn-medium btn-base-color btn-round-edge btn-box-shadow mb-20px mt-25px submit w-100"
                                type="submit">Mesaj Gönder<i class="fa-regular fa-envelope"></i>
                            </button>
{{--                              <p class="fs-13 lh-22 w-90 md-w-100">I understand that my data will be hold securely in--}}
{{--                                  accordance with the <a--}}
{{--                                      class="text-decoration-line-bottom text-dark-gray text-dark-gray-hover fw-500"--}}
{{--                                      href="#">privacy policy.</a>--}}
{{--                              </p>--}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end section -->
