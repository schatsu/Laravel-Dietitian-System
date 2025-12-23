@extends('front.layouts.base')
@section('page-title', 'Randevu Al')
@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .badge-slot {
            display: inline-block;
            cursor: pointer;
            border-radius: 20px;
            padding: 6px 14px;
            border: 1px solid #ccc;
            background-color: #f8f9fa;
            color: #333;
            font-size: 14px;
            transition: all 0.2s ease-in-out;
        }

        .badge-slot:hover {
            background-color: var(--base-color);;
            color: white;
        }
        #available-slots {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .badge-slot.disabled {
            background-color: #e9ecef;
            color: #adb5bd;
            cursor: not-allowed;
            border: 1px solid #dee2e6;
        }

        .badge-slot.active {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }
    </style>
@endpush
@section('content')
    <section class="ipad-top-space-margin md-pt-0">
        <div class="container">
            <div class="row justify-content-center mb-3">
                <div class="col-lg-12 text-center appear anime-child anime-complete"
                     data-anime="{ &quot;el&quot;: &quot;childs&quot;, &quot;translateY&quot;: [30, 0], &quot;opacity&quot;: [0,1], &quot;duration&quot;: 600, &quot;delay&quot;: 0, &quot;staggervalue&quot;: 300, &quot;easing&quot;: &quot;easeOutQuad&quot; }">
                    <h2 class="text-dark-gray  ls-minus-1px">Randevu Al</h2>
                    <div class="mt-auto justify-content-start breadcrumb breadcrumb-style-01 fs-14 text-dark-gray">
                        <ul>
                            <li><a href="{{route('home')}}"
                                   class="text-dark-gray text-dark-gray-hover">Ana sayfa</a></li>
                            <li><a href="{{route('appointments.index')}}"
                                   class="text-dark-gray fw-bold text-dark-gray-hover">Randevu Al</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container"
             data-anime='{ "el": "childs", "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 300, "easing": "easeOutQuad" }'>
            <div class="row mb-3">
                <div class="col text-center">
                    <h3 class="fw-500 text-gray ls-minus-2px">Hayalindeki vücuda bir adım at.</h3>
                </div>
            </div>
            <div class="row align-items-center justify-content-center position-relative z-index-1">
                <div class="col-xl-10 col-lg-12">
                    <form action="{{route('appointments.store')}}" method="post" class="row contact-form-style-02">
                        @csrf
                        <div class="col-md-6 mb-30px">
                            <input
                                class="form-control border-radius-4px border-color-white box-shadow-double-large required"
                                type="text"
                                name="name"
                                placeholder="Ad Soyad*"
                                value="{{ old('name') }}"
                                required
                            />
                        </div>

                        <div class="col-md-6 mb-30px">
                            <input
                                class="form-control border-radius-4px border-color-white box-shadow-double-large required"
                                type="email"
                                name="email"
                                placeholder="E-posta Adresi*"
                                value="{{ old('email') }}"
                                required
                            />
                        </div>

                        <div class="col-md-6 mb-30px">
                            <input
                                class="form-control border-radius-4px border-color-white box-shadow-double-large required"
                                type="tel"
                                name="phone"
                                placeholder="Telefon Numarası*"
                                value="{{ old('phone') }}"
                                required
                            />
                        </div>

                        <div class="col-md-6 mb-30px">
                            <input id="appointment-date"
                                   class="form-control border-radius-4px"
                                   type="text"
                                   name="date"
                                   placeholder="Tarih seçin"
                                   required>
                        </div>

                        <div class="col-12 mb-30px d-none" id="time-section">
                            <label class="form-label fw-bold mb-2">Uygun Saatler</label>
                            <div id="available-slots" class="d-flex flex-wrap gap-2"></div>

                            <div id="selected-slot" class="mt-2 text-primary fw-semibold"></div>
                        </div>

                        <input type="hidden" name="start_time" id="start-time-input">
                        <input type="hidden" name="end_time" id="end-time-input">

                        <div class="col-md-12 mb-3">
                            <textarea
                                class="form-control border-radius-4px border-color-white box-shadow-double-large"
                                cols="40"
                                rows="4"
                                name="note"
                                placeholder="Eklemek istediğiniz not"
                            >{{ old('note') }}</textarea>
                        </div>

                        <div class="col-xl-6 col-md-5 text-center text-md-end sm-mt-20px ms-auto">
                            <button
                                class="btn btn-base-color btn-switch-text btn-small left-icon btn-round-edge text-transform-none"
                                type="submit"
                            >
                            <span>
                                <span><i class="bi bi-calendar-check"></i></span>
                                <span class="btn-double-text" data-text="Acele et!">Randevu Al</span>
                            </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slotSection = document.getElementById('time-section');
            const availableSlots = document.getElementById('available-slots');
            const selectedSlot = document.getElementById('selected-slot');
            const startTimeInput = document.getElementById('start-time-input');
            const endTimeInput = document.getElementById('end-time-input');

            flatpickr("#appointment-date", {
                dateFormat: "Y-m-d",
                minDate: "today",
                locale: "tr",
                onChange: function (selectedDates, dateStr) {
                    if (!dateStr) return;

                    availableSlots.innerHTML = '<div class="spinner-border text-primary sm" role="status"></div>';
                    selectedSlot.innerHTML = '';
                    startTimeInput.value = '';
                    endTimeInput.value = '';
                    slotSection.classList.remove('d-none');

                    axios.get(`{{route('appointment-slots.by-date')}}`, {params: {date: dateStr}})
                        .then(response => {
                            const slots = response.data.data;
                            availableSlots.innerHTML = '';

                            if (!slots || slots.length === 0) {
                                availableSlots.innerHTML = '<span class="text-muted">Bu tarihte uygun saat yok.</span>';
                                return;
                            }

                            slots.forEach(slot => {
                                const start = slot.start_time;
                                const end = slot.end_time;

                                const isAvailable = slot.available;

                                const badge = document.createElement('span');
                                badge.className = 'badge-slot';
                                badge.textContent = `${start} - ${end}`;

                                if (!isAvailable) {
                                    badge.classList.add('disabled');

                                    if (slot.is_past) {
                                        badge.title = 'Bu saatin vakti geçmiş';
                                        badge.classList.add('past-slot');
                                    } else if (slot.is_booked) {
                                        badge.title = 'Bu saat dolu';
                                        badge.classList.add('booked-slot');
                                    } else {
                                        badge.title = 'Seçilemez';
                                    }
                                } else {
                                    badge.style.cursor = 'pointer';
                                    badge.addEventListener('click', () => {
                                        document.querySelectorAll('.badge-slot').forEach(b => b.classList.remove('active'));
                                        badge.classList.add('active');

                                        selectedSlot.innerHTML = `Seçilen Saat: <strong>${start} - ${end}</strong>`;
                                        startTimeInput.value = start;
                                        endTimeInput.value = end;
                                    });
                                }

                                availableSlots.appendChild(badge);
                            });
                        })
                        .catch(error => {
                            console.error('Hata:', error);
                            availableSlots.innerHTML = '<span class="text-danger">Saatler yüklenirken bir hata oluştu.</span>';
                        });
                }
            });
        });
    </script>
@endpush
