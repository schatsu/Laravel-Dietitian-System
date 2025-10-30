<!-- start section -->
<section class="">
    <div class="container">
        <h3 class="text-dark-gray fw-700 text-center">Hizmetler</h3>
        <div class="row row-cols-1 row-cols-lg-2">
            <!-- start interactive banner item -->
            @foreach($services as $service)
                <div class="col interactive-banner-style-08 md-mb-30px mb-lg-4">
                    <figure class="m-0 hover-box overflow-hidden position-relative border-radius-6px">
                        <img src="https://placehold.co/1160x640" alt="" />
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
    </div>
</section>
<!-- end section -->
