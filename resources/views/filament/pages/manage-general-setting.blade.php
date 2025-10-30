<x-filament::page>
    {{-- Form burada otomatik gelecek --}}
    {{ $this->form }}
    <div>
        <x-filament::button type="button" class="mt-4" wire:click="save">
            Kaydet
        </x-filament::button>
    </div>


    {{-- Google Maps API Key kontrolü --}}
    @if(config('services.google.maps_api_key'))
        {{-- Google Maps Script --}}
        <script>
            // Global değişken tanımla
            window.googleMapsLoaded = false;

            function initAutocomplete() {
                window.googleMapsLoaded = true;
                setupAddressAutocomplete();
            }

            function setupAddressAutocomplete(callback) {
                let input = document.querySelector('input[wire\\:model="data.address"], input[name="address"]');

                if (!input) {
                    console.error('Address input field not found');
                    // 1 saniye sonra tekrar dene
                    setTimeout(setupAddressAutocomplete, 1000);
                    return;
                }

                try {
                    // Google Places Autocomplete başlat
                    const autocomplete = new google.maps.places.Autocomplete(input, {
                        types: ['establishment', 'geocode'],
                        componentRestrictions: { country: 'tr' }
                    });

                    autocomplete.addListener('place_changed', function () {
                        const place = autocomplete.getPlace();

                        if (!place.geometry || !place.geometry.location) {
                            return;
                        }


                        const latInput = document.querySelector('input[wire\\:model="data.latitude"], input[name="latitude"]');
                        const lngInput = document.querySelector('input[wire\\:model="data.longitude"], input[name="longitude"]');

                        if (latInput) {
                            latInput.value = place.geometry.location.lat();
                            console.log(latInput.value)
                            latInput.dispatchEvent(new Event('input', {bubbles: true}));
                        }

                        if (lngInput) {
                            lngInput.value = place.geometry.location.lng();
                            lngInput.dispatchEvent(new Event('input', {bubbles: true}));
                        }

                        if (input) {
                            input.dispatchEvent(new Event('input', {bubbles: true}));

                            google.maps.event.trigger(input, 'blur');
                        }

                    });

                } catch (error) {
                    console.error('Error initializing autocomplete:', error);
                }
            }

            // Document ready olduğunda çalıştır
            document.addEventListener('DOMContentLoaded', function() {
                if (window.googleMapsLoaded) {
                    setupAddressAutocomplete();
                }
            });

            // Livewire event listener'ları - güvenli şekilde
            document.addEventListener('livewire:navigated', function() {
                if (window.googleMapsLoaded) {
                    setTimeout(setupAddressAutocomplete, 500);
                }
            });

            // Livewire yüklendiğinde hook'ları ekle
            document.addEventListener('livewire:init', function() {
                if (typeof Livewire !== 'undefined') {
                    Livewire.hook('component.initialized', (component) => {
                        if (window.googleMapsLoaded) {
                            setTimeout(setupAddressAutocomplete, 500);
                        }
                    });

                    Livewire.hook('morph.updated', (el, component) => {
                        if (window.googleMapsLoaded) {
                            setTimeout(setupAddressAutocomplete, 500);
                        }
                    });
                }
            });

            // Alternatif olarak, Livewire yüklenmesini bekle
            function waitForLivewire(callback) {
                if (typeof Livewire !== 'undefined') {
                    callback();
                } else {
                    setTimeout(() => waitForLivewire(callback), 100);
                }
            }

            // Page load'da Livewire'ı bekle
            window.addEventListener('load', function() {
                waitForLivewire(function() {
                    if (window.googleMapsLoaded) {
                        setTimeout(setupAddressAutocomplete, 1000);
                    }
                });
            });
        </script>

        {{-- Google Maps API'yi asynchronously yükle --}}
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places&callback=initAutocomplete&loading=async"></script>
    @else
        <script>
            console.error('Google Maps API key is not configured. Please set GOOGLE_MAPS_API_KEY in your .env file');
        </script>
    @endif

    {{-- Google Places Autocomplete için özel stiller --}}
    <style>
        /* Ana container */
        .pac-container {
            z-index: 9999 !important;
            border-radius: 12px !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            background: white !important;
            margin-top: 4px !important;
            font-family: 'Inter', system-ui, sans-serif !important;
            overflow: hidden !important;
        }

        /* Dark mode için */
        .dark .pac-container {
            background: #18181B !important;
            border-color: #374151 !important;
            color: #f9fafb !important;
        }

        /* Her bir adres önerisi */
        .pac-item {
            padding: 12px 16px !important;
            border-bottom: 1px solid #f3f4f6 !important;
            cursor: pointer !important;
            transition: all 0.15s ease-in-out !important;
            font-size: 14px !important;
            line-height: 1.4 !important;
        }

        /* Dark mode için item */
        .dark .pac-item {
            border-bottom-color: #374151 !important;
        }

        /* Son item'ın border'ını kaldır */
        .pac-item:last-child {
            border-bottom: none !important;
        }

        /* Hover efekti */
        .pac-item:hover {
            background-color: #f8fafc !important;
            transform: translateX(2px) !important;
        }

        /* Dark mode hover */
        .dark .pac-item:hover {
            background-color: #374151 !important;
        }

        /* Seçili item */
        .pac-item-selected {
            background-color: #3b82f6 !important;
            color: white !important;
        }

        /* Ana metin (adres) */
        .pac-item-query {
            font-weight: 600 !important;
            color: #1f2937 !important;
            margin-bottom: 2px !important;
        }

        /* Dark mode ana metin */
        .dark .pac-item-query {
            color: #f9fafb !important;
        }

        /* Seçili item'da ana metin */
        .pac-item-selected .pac-item-query {
            color: white !important;
        }

        /* Alt metin (detaylar) */
        .pac-item-query + span {
            font-size: 12px !important;
            color: #6b7280 !important;
            font-weight: 400 !important;
        }

        /* Dark mode alt metin */
        .dark .pac-item-query + span {
            color: #9ca3af !important;
        }

        /* Seçili item'da alt metin */
        .pac-item-selected .pac-item-query + span {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        /* İkon için stil */
        .pac-icon {
            width: 20px !important;
            height: 20px !important;
            margin-right: 12px !important;
            margin-top: 2px !important;
            opacity: 0.7 !important;
        }

        /* Match edilen kelimeler için */
        .pac-matched {
            font-weight: 700 !important;
            color: #3b82f6 !important;
        }

        /* Seçili item'da matched */
        .pac-item-selected .pac-matched {
            color: white !important;
        }

        /* Loading animation */
        .pac-container:empty::before {
            content: "Adresler yükleniyor...";
            display: block;
            padding: 16px;
            color: #6b7280;
            font-style: italic;
            text-align: center;
            font-size: 14px;
        }

        /* Dark mode loading */
        .dark .pac-container:empty::before {
            color: #9ca3af;
        }

        /* Responsive tasarım */
        @media (max-width: 640px) {
            .pac-container {
                left: 0 !important;
                right: 0 !important;
                width: auto !important;
                margin: 4px !important;
                border-radius: 8px !important;
            }

            .pac-item {
                padding: 10px 12px !important;
                font-size: 13px !important;
            }
        }

        /* Custom scrollbar */
        .pac-container {
            max-height: 300px !important;
            overflow-y: auto !important;
        }

        .pac-container::-webkit-scrollbar {
            width: 6px;
        }

        .pac-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .pac-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .pac-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Dark mode scrollbar */
        .dark .pac-container::-webkit-scrollbar-track {
            background: #374151;
        }

        .dark .pac-container::-webkit-scrollbar-thumb {
            background: #6b7280;
        }

        .dark .pac-container::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        /* Powered by Google logosu için */
        .pac-logo::after {
            opacity: 0.6 !important;
        }

        /* Input field focus'ta border rengi */
        input[name="address"]:focus + .pac-container {
            border-color: #3b82f6 !important;
        }

        /* Animasyon efektleri */
        .pac-container {
            animation: fadeInDown 0.2s ease-out !important;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Modern görünüm için ek efektler */
        .pac-item {
            position: relative;
        }

        .pac-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: transparent;
            transition: background 0.15s ease-in-out;
        }

        .pac-item:hover::before {
            background: #3b82f6;
        }

        .pac-item-selected::before {
            background: white !important;
        }
    </style>
</x-filament::page>
