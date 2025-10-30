@extends('front.layouts.base')
@push('css')
<style>
    .blog-name {
        white-space: nowrap;
    !important;
        overflow: hidden;
    !important;
        text-overflow: ellipsis;
    !important;
    }

    #filterOffcanvas .list-group-item > a {
        color: var(--medium-gray);
    !important;
        border-color: var(--dark-gray) !important;
    }

    #filterOffcanvas .list-group-item.active {
        background-color: transparent !important;
        border: none !important;
    }

    #filterOffcanvas .list-group-item.active > a {
        color: var(--dark-gray) !important;
        border-bottom: 2px solid transparent;
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
                    <h2 class="text-dark-gray ls-minus-1px">Blog</h2>
                    <div class="mt-auto justify-content-start breadcrumb breadcrumb-style-01 fs-14 text-dark-gray">
                        <ul>
                            <li><a href="{{route('home')}}"
                                   class="text-dark-gray text-dark-gray-hover">Ana sayfa</a></li>
                            <li><a href="{{route('blogs.index')}}"
                                   class="text-dark-gray fw-bold text-dark-gray-hover">Blog</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row align-items-center mb-4 lg-mb-3">
                <div class="col-12 tab-style-04">
                    @if($categories->isNotEmpty())
                        <ul class="category-filter nav nav-tabs justify-content-center text-center border-0 fw-500 d-none d-lg-flex">
                            <li class="nav active"><a data-filter="*" href="#">Tümü</a></li>
                            @foreach($categories as $category)
                                <li class="nav">
                                    <a data-filter=".category-{{ $category->id }}" href="#">{{$category?->name}}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <button class="btn btn-outline-dark btn-lg d-block d-lg-none" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas"
                            aria-controls="filterOffcanvas">
                        <i class="bi bi-filter"></i> {{__('messages.filter')}}
                    </button>

                    <div class="offcanvas offcanvas-top" tabindex="-1" id="filterOffcanvas"
                         aria-labelledby="filterOffcanvasLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title text-dark-gray fw-700 ls-minus-1px"
                                id="filterOffcanvasLabel">{{__('messages.categories')}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                    aria-label="Kapat"></button>
                        </div>
                        <div class="offcanvas-body">
                            @if($categories->isNotEmpty())
                                <ul class="list-group">
                                    <li class="list-group-item"><a data-filter="*" href="#">{{__('messages.all')}}</a>
                                    </li>
                                    @foreach($categories as $category)
                                        <li class="list-group-item">
                                            <a data-filter=".category-{{ $category->id }}"
                                               href="#">{{$category?->name}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 ">
                    <ul class="blog-side-image portfolio-wrapper grid-loading grid grid-3col xxl-grid-2col xl-grid-2col lg-grid-2col md-grid-1col sm-grid-1col xs-grid-1col gutter-extra-large">
                        <li class="grid-sizer"></li>
                        <!-- start portfolio item -->
                        @forelse($blogs as $blog)
                            <li class="grid-item blog-item category-{{ $blog->category_id }} mb-5">
                                <div
                                    class="blog-box d-md-flex d-block flex-row h-100 border-radius-6px overflow-hidden box-shadow-extra-large">
                                    <div class="blog-image w-50 sm-w-100 cover-background"
                                         style="background-image: url('{{ $blog->getFirstMediaUrl('blog', 'cover_image') ?: 'https://placehold.co/800x923' }}')">
                                        <a href="{{ route('blogs.show', ['blog' => $blog->slug]) }}"></a>
                                    </div>
                                    <div class="blog-content w-50 sm-w-100 pt-50px pb-40px ps-40px pe-40px xl-p-30px bg-white d-flex flex-column justify-content-center align-items-start last-paragraph-no-margin">
                                        <a href="#"
                                           class="categories-btn bg-dark-gray text-white text-uppercase fw-500 mb-30px"
                                           data-filter=".category-{{$blog->category_id }}">
                                            {{ $blog->category?->name ?? '' }}
                                        </a>

                                        <a data-bs-toggle="tooltip" data-bs-placement="top"
                                           data-bs-title="{{$blog?->title ?? ''}}"
                                           href="{{ route('blogs.show', ['blog' => $blog->slug]) }}"
                                           class="card-title text-dark-gray text-dark-gray-hover mb-5px fw-600 fs-18 lh-28">{{$blog?->title_formatted ?? ''}}</a>

                                        <p data-bs-toggle="tooltip" data-bs-placement="top"
                                           data-bs-title="{{$blog?->description ?? ''}}"
                                           title="{{$blog?->description ?? ''}}">{{ $blog?->description_formatted }}</p>

                                        <div class="mt-15px">
                                            <span class="separator bg-dark-gray"></span>
                                            <a href="{{ route('blogs.show', ['blog' => $blog->slug]) }}"
                                               class="text-dark-gray text-dark-gray-hover d-inline-block fs-15 fw-500 fw-500">{{auth()->user()?->name ?? ''}}</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                        @endforelse
                        <!-- end blog item -->
                    </ul>
                </div>
                <div class="col-12 d-flex justify-content-center mb-3">
                    <ul class="pagination pagination-style-01 fs-13 fw-500 mb-0">
                        {{$blogs->links('vendor.pagination.custom')}}
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- end section -->
@endsection
@push('script')
    <script>
        $(document).on("click", "#filterOffcanvas .list-group-item > a", function (e) {
            e.preventDefault();

            const $link = $(this);
            const $listGroup = $("#filterOffcanvas .list-group-item");
            const $section = $(".portfolio-wrapper").closest("section");
            const selector = $link.data("filter");
            const $portfolioFilter = $section.find(".portfolio-wrapper");

            $listGroup.removeClass("active");
            $link.parent().addClass("active");

            $portfolioFilter.find(".grid-item[data-anime]").addClass("appear");
            if (typeof $portfolioFilter.isotope === "function") {
                $portfolioFilter.isotope({filter: selector});
            }

            if ($section.length && $section.hasClass("overlap-height")) {
                setOverLayerPosition();
            }

            const offcanvasEl = document.getElementById("filterOffcanvas");
            const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
            if (offcanvas) offcanvas.hide();
        });
        $(document).on('click', '.category-filter > li > a, .categories-btn', function (e) {
            e.preventDefault();

            const $this = $(this);
            const $parentSection = $this.closest('section');
            const selector = $this.data('filter');
            const $portfolioFilter = $parentSection.find('.portfolio-wrapper');


            if ($this.closest('.category-filter').length) {
                $parentSection.find('.category-filter > li').removeClass('active');
                $this.closest('li').addClass('active');
            } else if ($this.hasClass('categories-btn')) {
                const selector = $this.data('filter');
                $parentSection.find('.category-filter > li').removeClass('active');
                $parentSection.find(`.category-filter > li > a[data-filter="${selector}"]`)
                    .parent()
                    .addClass('active');
            }


            if (typeof $portfolioFilter.isotope === 'function') {
                $portfolioFilter.isotope({filter: selector});
            }

            if ($parentSection.hasClass('overlap-height')) {
                setOverLayerPosition();
            }
        });
    </script>
@endpush
