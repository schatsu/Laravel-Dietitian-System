@extends('front.layouts.base')
@section('page-title', $page?->title)
{{--@push('meta')--}}
{{--    <meta name="title" content="{{ \App\Helpers\SeoHelper::getTitle($page?->seo_title) ?? '' }}">--}}
{{--    <meta name="description" content="{{ \App\Helpers\SeoHelper::getDescription($page?->seo_description) ?? '' }}">--}}
{{--    <meta name="keywords" content="{{ \App\Helpers\SeoHelper::getKeywords($page?->seo_keywords) ?? '' }}">--}}

{{--    <meta property="og:title" content="{{ $og['title'] }}">--}}
{{--    <meta property="og:description" content="{{ $og['description'] }}">--}}
{{--    <meta property="og:type" content="{{ $og['type'] }}">--}}
{{--    <meta property="og:url" content="{{ $og['url'] }}">--}}
{{--    <meta property="og:image" content="{{ $og['image'] }}">--}}
{{--    <meta property="og:locale" content="{{ $og['locale'] }}">--}}
{{--    <meta property="og:site_name" content="{{ $og['site_name'] }}">--}}
{{--    <meta property="og:image:alt" content="{{ $og['site_name'] }}"/>--}}

{{--    --}}{{-- Twitter --}}
{{--    <meta name="twitter:card" content="summary_large_image">--}}
{{--    <meta name="twitter:title" content="{{ $og['title'] }}">--}}
{{--    <meta name="twitter:description" content="{{ $og['description'] }}">--}}
{{--    <meta name="twitter:image" content="{{ $og['image'] }}">--}}
{{--    <meta name="twitter:image:alt" content="{{ $og['title'] }}">--}}


{{--    --}}{{-- Canonical --}}
{{--    <link rel="canonical" href="{{ \App\Helpers\SeoHelper::getCanonical() }}">--}}

{{--    --}}{{-- Hreflang etiketleri --}}
{{--    @foreach(\App\Helpers\SeoHelper::getHreflangs() as $locale => $url)--}}
{{--        <link rel="alternate" href="{{ $url }}" hreflang="{{ $locale }}"/>--}}
{{--    @endforeach--}}

{{--    --}}{{-- Google varsayılan dil (x-default) --}}
{{--    <link rel="alternate" href="{{ \App\Helpers\SeoHelper::getCanonical() }}" hreflang="x-default"/>--}}
{{--@endpush--}}
@section('content')
    <section class="ipad-top-space-margin md-pt-0">
        <div class="container">
            <div class="row justify-content-center mb-3">
                <div class="col-lg-12 text-center appear anime-child anime-complete"
                     data-anime="{ &quot;el&quot;: &quot;childs&quot;, &quot;translateY&quot;: [30, 0], &quot;opacity&quot;: [0,1], &quot;duration&quot;: 600, &quot;delay&quot;: 0, &quot;staggervalue&quot;: 300, &quot;easing&quot;: &quot;easeOutQuad&quot; }">
                    <h2 class="text-dark-gray ls-minus-1px">{{$page?->title}}</h2>
                    <div class="mt-auto justify-content-start breadcrumb breadcrumb-style-01 fs-14 text-dark-gray">
                        <ul>
                            <li><a href="{{route('home')}}"
                                   class="text-dark-gray text-dark-gray-hover">Ana sayfa</a></li>
                            <li><a href="{{route('page.show', ['slug' => $page?->slug])}}"
                                   class="text-dark-gray fw-bold text-dark-gray-hover">{{$page?->title}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-12">
                    {!! str($page->content)->sanitizeHtml() !!}
                </div>
            </div>
        </div>
    </section>
@endsection
