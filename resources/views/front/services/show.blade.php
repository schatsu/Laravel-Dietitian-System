@extends('front.layouts.base')
@section('page-title', $service?->name ?? '')
@push('css')
    <style>
        .attachment__caption {
            display: none !important;
        }
        .body {
            color: #000 !important;
        }
        .start-blog-content ul li {
            list-style-type: disc !important;
        }
    </style>
@endpush
@section('content')
    <section class="ipad-top-space-margin md-pt-0">
        <div class="container">
            <div class="row justify-content-center mb-3">
                <div class="col-lg-12 text-center appear anime-child anime-complete" data-anime="{ &quot;el&quot;: &quot;childs&quot;, &quot;translateY&quot;: [30, 0], &quot;opacity&quot;: [0,1], &quot;duration&quot;: 600, &quot;delay&quot;: 0, &quot;staggervalue&quot;: 300, &quot;easing&quot;: &quot;easeOutQuad&quot; }">
                    <h2 class="text-dark-gray ls-minus-1px mb-4">{{$service?->name ?? ''}}</h2>
                    <div class="mt-auto justify-content-start breadcrumb breadcrumb-style-01 fs-14 text-dark-gray">
                        <ul>
                            <li><a href="{{route('home')}}" class="text-dark-gray text-dark-gray-hover">Ana sayfa</a></li>
                            <li><a href="{{route('services.index')}}" class="text-dark-gray text-dark-gray-hover">Hizmetlerim</a></li>
                            <li><a href="{{route('services.show', ['service' => $service?->slug])}}" class="text-dark-gray fw-bold text-dark-gray-hover">{{$service?->name ?? ''}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center start-blog-content">
                <div class="col-12">
                    {!! str($service->content)->sanitizeHtml() !!}
                </div>
            </div>
            <div class="row justify-content-center mt-4">
                <div class="col-auto text-center last-paragraph-no-margin icon-with-text-style-08 pt-20px pb-20px ps-8 pe-8 md-ps-30px md-pe-30px bg-white border border-color-extra-medium-gray box-shadow-medium-bottom border-radius-100px xs-border-radius-10px">
                    <div class="feature-box feature-box-left-icon-middle overflow-hidden">
                        <div class="feature-box-icon me-10px">
                            <i class="bi bi-chat-text icon-extra-medium text-base-color"></i>
                        </div>
                        <div class="feature-box-content last-paragraph-no-margin text-dark-gray text-uppercase fs-15 fw-700 ls-05px">
                            Gelin birlikte harika bir iş çıkaralım. <a href="{{route('appointments.index')}}" class="text-base-color text-decoration-line-bottom-medium border-1">Randevu Al</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- end section -->
@endsection
@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll("a[href*='storage']").forEach(el => {
                let img = el.querySelector("img");
                if (img) {
                    el.replaceWith(img);
                }
            });
        });
    </script>
@endpush
