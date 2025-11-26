<div>
    <section class="bg-gradient-quartz-white border-radius-6px lg-border-radius-0px pb-0">
        <div class="container">
            <div class="row justify-content-center mb-3">
                <div class="col-lg-7 text-center"
                     data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                    <h3 class="text-dark-gray fw-700 ls-minus-1px">Öne Çıkan Bloglar</h3>
                </div>
            </div>
            <div class="row mb-5 sm-mb-7">
                <div class="col-12">
                    <ul class="blog-grid blog-wrapper grid-loading grid grid-3col xl-grid-3col lg-grid-3col md-grid-2col sm-grid-2col xs-grid-1col gutter-extra-large" data-anime='{ "el": "childs", "translateY": [50, 0], "opacity": [0,1], "duration": 600, "delay":0, "staggervalue": 150, "easing": "easeOutQuad" }'>
                        <li class="grid-sizer"></li>
                        <!-- start blog item -->
                        @forelse ($featuredBlogs as $blog)
                            <li class="grid-item">
                                <div class="card border-0 border-radius-5px box-shadow-quadruple-large box-shadow-quadruple-large-hover">
                                    <div class="blog-image">
                                        <a href="{{route('blogs.show', ['blog' => $blog?->slug])}}" class="d-block">
                                            <img src="{{$blog?->getFirstMediaUrl('blogs', 'cover_image')}}" alt="{{$blog?->title}}" />
                                        </a>
                                        <div class="blog-categories">
                                            <a href="{{route('blogs.show', ['blog' => $blog?->slug])}}" class="categories-btn bg-white text-dark-gray text-dark-gray-hover text-uppercase fw-700">
                                                {{$blog?->category?->name ?? ''}}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body p-12 lg-p-10">
                                        <a href="{{route('blogs.show', ['blog' => $blog?->slug])}}" class="card-title mb-15px fw-700 fs-19 text-dark-gray d-inline-block w-90 md-w-100">
                                            {{$blog?->title}}
                                        </a>
                                        <p>{{\Illuminate\Support\Str::limit($blog?->description, 40)}}</p>
                                        <div class="author d-flex justify-content-center align-items-center position-relative overflow-hidden fs-14 text-uppercase">
                                            <div class="me-auto">
                                                <span class="blog-date d-inline-block fw-700 text-dark-gray">{{$blog?->created_at->format('d/m/y')}}</span>
                                                <div class="d-inline-block author-name fw-700 text-dark-gray">
                                                    <a href="{{route('blogs.show', ['blog' => $blog?->slug])}}" class="text-dark-gray text-decoration-line-bottom">
                                                        Ayşe Yılmaz
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                        @endforelse
                        <!-- end blog item -->
                    </ul>
                </div>
            </div>
        </div>
    </section>
</div>
