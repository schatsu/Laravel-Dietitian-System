@extends('front.layouts.base')
@section('page-title', $blog?->name ?? '')
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
                    <h2 class="text-dark-gray ls-minus-1px mb-4">{{$blog?->title ?? ''}}</h2>
                    <div class="mt-auto justify-content-start breadcrumb breadcrumb-style-01 fs-14 text-dark-gray">
                        <ul>
                            <li><a href="{{route('home')}}" class="text-dark-gray text-dark-gray-hover">Ana sayfa</a></li>
                            <li><a href="{{route('blogs.index')}}" class="text-dark-gray text-dark-gray-hover">Blog</a></li>
                            <li><a href="{{route('blogs.show', ['blog' => $blog?->slug])}}" class="text-dark-gray fw-bold text-dark-gray-hover">{{$blog?->title ?? ''}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center start-blog-content">
                <div class="col-12">
                    {!! str($blog->content)->sanitizeHtml() !!}
                </div>
                @if($blog?->tags->count() > 0)
                    <div class="col-lg-12 last-paragraph-no-margin">
                        <div class="row mb-30px">
                            <p class="fw-bold mb-1">Etiketler</p>
                            <div class="tag-cloud col-md-9 text-center text-md-start sm-mb-15px">
                                @foreach($blog->tags as $tag)
                                    <a class="fw-bold text-dark" href="{{route('tags.show', ['tag' => $tag->slug])}}">#{{$tag?->name}}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
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
