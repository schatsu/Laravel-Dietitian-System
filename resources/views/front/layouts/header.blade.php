<!-- start header -->
<header>
    <!-- start navigation -->
    <nav class="navbar navbar-expand-lg mini-header header-light bg-white header-reverse header-demo glass-effect"
         data-header-hover="light">
        <div class="container-fluid">
            <div class="col-auto me-auto ps-lg-0">
                <a class="navbar-brand" href="{{route('home')}}">
                    <img src="{{ asset('storage/' . $generalSetting?->site_logo_dark) ?? '' }}"
                         data-at2x="{{ asset('storage/' . $generalSetting?->site_logo_dark) ?? '' }}"
                         alt="{{$generalSetting?->site_name}}" class="default-logo">
                    <img src="{{ asset('storage/' . $generalSetting?->site_logo_dark) ?? '' }}"
                         data-at2x="{{ asset('storage/' . $generalSetting?->site_logo_dark) ?? '' }}"
                         alt="{{$generalSetting?->site_name}}" class="alt-logo">
                    <img src="{{ asset('storage/' . $generalSetting?->site_logo_dark) ?? '' }}"
                         data-at2x="{{ asset('storage/' . $generalSetting?->site_logo_dark) ?? '' }}"
                         alt="{{$generalSetting?->site_name}}" class="mobile-logo">
                </a>
            </div>
            <div class="col-auto col-lg-8 menu-order position-static">
                <button class="navbar-toggler float-start" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNav" aria-controls="navbarNav" aria-label="Toggle navigation">
                    <span class="navbar-toggler-line"></span>
                    <span class="navbar-toggler-line"></span>
                    <span class="navbar-toggler-line"></span>
                    <span class="navbar-toggler-line"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a href="{{route('home')}}" class="nav-link">Ana Sayfa</a></li>
                        <li class="nav-item"><a href="{{route('about')}}" class="nav-link">Hakkımda</a></li>
                        <li class="nav-item dropdown dropdown-with-icon-style02">
                            <a href="{{route('services.index')}}" class="nav-link">Hizmetlerim</a>
                            <i class="fa-solid fa-angle-down dropdown-toggle" id="navbarDropdownMenuLink" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                @forelse($headerServices as $service)
                                    <li>
                                        <a href="{{route('services.show', ['service' => $service?->slug])}}">{{$service?->name ?? ''}}</a>
                                    </li>
                                @empty
                                @endforelse
                            </ul>
                        </li>
                        <li class="nav-item"><a href="{{route('blogs.index')}}" class="nav-link">Blog</a></li>
                        <li class="nav-item"><a href="{{route('contact')}}" class="nav-link">İletişim</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-auto col-lg-2 text-end xs-pe-0">
                <div class="header-icon">
                    <div class="header-search-icon icon">
                        <a href="javascript:void(0)" class="search-form-icon header-search-form"><i
                                class="feather icon-feather-search"></i></a>
                        <div class="search-form-wrapper">
                            <button title="Close" type="button" class="search-close alt-font">×</button>
                            <form id="search-form" role="search" method="get" class="search-form text-left"
                                  action="#">
                                <div class="search-form-box">
                                    <h2 class="text-dark-gray text-center mb-7 fw-600">Ne arıyorsunuz?</h2>
                                    <input class="search-input alt-font" id="search-form-input5e219ef164995"
                                           placeholder="Anahtar kelimelerinizi giriniz..." name="s" value="" type="text"
                                           autocomplete="off">
                                    <button type="submit" class="search-button">
                                        <i class="feather icon-feather-search" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="header-button ms-20px d-none d-xl-inline-block">
                        <a href="{{route('appointments.index')}}"
                           class="btn btn-very-small btn-switch-text btn-base-color left-icon btn-round-edge btn-box-shadow">
                              <span>
                                 <span><i class="feather icon-feather-calendar"></i></span>
                                 <span class="btn-double-text"
                                       data-text="Acele et!">Randevu Al</span>
                              </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- end navigation -->
</header>
<!-- end header -->
