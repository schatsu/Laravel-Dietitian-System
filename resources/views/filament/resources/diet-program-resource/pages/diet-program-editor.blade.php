<x-filament-panels::page>
    <x-filament-tables::container>
        {{-- Yatay kaydırma için wrapper --}}
        <div class="overflow-x-auto shadow-lg rounded-xl border border-gray-200 dark:border-gray-700 bg-gradient-to-br from-white to-gray-50 dark:from-gray-900 dark:to-gray-800">
            <table class="w-full table-auto text-start fi-ta-table border-collapse min-w-[700px] backdrop-blur-sm">
                {{-- Tablo Başlıkları --}}
                <thead class="bg-gradient-to-r from-primary-500 via-primary-600 to-primary-500 text-white shadow-lg">
                <tr>
                    <x-filament-tables::header-cell class="px-6 py-5 text-center w-36 font-bold border-r border-primary-400 relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                        <div class="relative flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-extrabold tracking-wide">Öğün / Gün</span>
                        </div>
                    </x-filament-tables::header-cell>
                    @foreach($days as $dayKey => $dayLabel)
                        <x-filament-tables::header-cell class="px-4 py-5 text-center min-w-[160px] font-bold border-r border-primary-400 last:border-r-0 relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                            <div class="relative flex items-center justify-center gap-2">
                                <div class="w-2.5 h-2.5 bg-white rounded-full shadow-sm animate-bounce" style="animation-delay: {{ $loop->index * 0.2 }}s"></div>
                                <span class="text-sm font-extrabold tracking-wide">{{ $dayLabel }}</span>
                            </div>
                        </x-filament-tables::header-cell>
                    @endforeach
                </tr>
                </thead>

                {{-- Tablo Gövdesi --}}
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($times as $timeKey => $timeLabel)
                    <x-filament-tables::row class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-gray-100/50 dark:hover:from-gray-800 dark:hover:to-gray-700/50 transition-all duration-300 group">
                        {{-- Öğün Adı --}}
                        <x-filament-tables::cell class="font-bold text-gray-900 dark:text-white px-6 py-5 w-36 bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-750 border-r border-gray-200 dark:border-gray-700 sticky left-0">
                            <div class="flex items-center gap-3">
                                @php
                                    $colors = [
                                        0 => 'from-rose-400 to-pink-500',
                                        1 => 'from-orange-400 to-amber-500',
                                        2 => 'from-green-400 to-emerald-500',
                                        3 => 'from-blue-400 to-indigo-500',
                                        4 => 'from-purple-400 to-violet-500'
                                    ];
                                    $colorClass = $colors[$loop->index % 5] ?? 'from-gray-400 to-gray-500';
                                @endphp
                                <div class="w-4 h-4 bg-gradient-to-br {{ $colorClass }} rounded-full shadow-lg animate-pulse"></div>
                                <span class="text-sm font-extrabold tracking-wide">{{ $timeLabel }}</span>
                            </div>
                        </x-filament-tables::cell>

                        {{-- Gün hücreleri --}}
                        @foreach($days as $dayKey => $dayLabel)
                            <x-filament-tables::cell
                                class="align-top min-h-[130px] p-3 border-r border-gray-100 dark:border-gray-800 last:border-r-0 bg-white dark:bg-gray-900 hover:bg-gradient-to-br hover:from-blue-50/30 hover:to-indigo-50/30 dark:hover:from-blue-900/10 dark:hover:to-indigo-900/10 transition-all duration-300 relative"
                            >
                                {{-- Hafif pattern arka plan --}}
                                <div class="absolute inset-0 opacity-5 bg-gradient-to-br from-primary-200 to-transparent pointer-events-none"></div>

                                <div class="space-y-2 min-h-[110px] relative z-10">
                                    @foreach($this->getItems($dayKey, $timeKey) as $item)
                                        <div
                                            class="group/item relative bg-gradient-to-br from-white via-blue-50/50 to-indigo-50/50 dark:from-gray-800 dark:via-blue-900/10 dark:to-indigo-900/10 rounded-xl p-3 border border-blue-200/60 dark:border-blue-700/40 hover:shadow-xl hover:scale-[1.03] hover:-translate-y-1 transition-all duration-300 backdrop-blur-sm"
                                        >
                                            {{-- Glow efekti --}}
                                            <div class="absolute inset-0 bg-gradient-to-br from-blue-400/10 to-purple-400/10 rounded-xl opacity-0 group-hover/item:opacity-100 transition-opacity duration-300"></div>

                                            {{-- Yemek adı --}}
                                            <div class="relative font-semibold text-gray-900 dark:text-white text-sm mb-3 leading-snug line-clamp-2">
                                                {{ $item['meal_name'] }}
                                            </div>

                                            {{-- Alt kısım --}}
                                            <div class="relative flex items-center justify-between">
                                                {{-- Miktar --}}
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-emerald-100 via-teal-100 to-cyan-100 text-emerald-800 dark:from-emerald-900/40 dark:via-teal-900/40 dark:to-cyan-900/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-700 shadow-sm">
                    <svg class="w-3 h-3 mr-1.5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    {{ $item['quantity'] }} {{ $item['unit'] }}
                </span>

                                                {{-- Sil butonu --}}
                                                <button
                                                    wire:click="removeItem({{ $item['id'] }})"
                                                    type="button"
                                                    class="p-2 hover:bg-gradient-to-r hover:from-red-100 hover:to-pink-100 dark:hover:from-red-900/30 dark:hover:to-pink-900/30 rounded-full transition-all duration-300 text-red-500 hover:text-red-700 dark:hover:text-red-400 hover:shadow-lg hover:scale-110"
                                                    title="Kaldır"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- Yemek ekleme butonu (her zaman görünsün) --}}
                                    <button
                                        wire:click="openAddMealModal('{{ $dayKey }}', '{{ $timeKey }}')"
                                        class="flex items-center justify-center h-full min-h-[110px] w-full border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl hover:border-primary-400 dark:hover:border-primary-500 hover:bg-gradient-to-br hover:from-primary-50/20 hover:to-blue-50/20 dark:hover:from-primary-900/10 dark:hover:to-blue-900/10 transition-all duration-300 group/empty cursor-pointer"
                                    >
                                        <div class="text-center py-6">
                                            <div class="relative mb-3">
                                                <svg class="w-10 h-10 text-gray-400 dark:text-gray-500 mx-auto group-hover/empty:text-primary-500 transition-colors duration-300 group-hover/empty:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                <div class="absolute inset-0 bg-gradient-to-br from-primary-200/20 to-blue-200/20 rounded-full opacity-0 group-hover/empty:opacity-100 transition-opacity duration-300 animate-pulse"></div>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 group-hover/empty:text-primary-600 dark:group-hover/empty:text-primary-400 transition-colors duration-300 font-semibold">
                                                <span class="block">✨ Yemek eklemek için</span>
                                                <span class="block font-bold">tıklayın</span>
                                            </p>
                                        </div>
                                    </button>
                                </div>

                            </x-filament-tables::cell>
                        @endforeach
                    </x-filament-tables::row>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Tablo Alt Bilgi Çubuğu --}}
        <div class="mt-4 p-4 bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-750 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-4">
                    <span class="text-gray-600 dark:text-gray-400">
                        📊 Toplam: <strong class="text-primary-600">{{ collect($table)->flatten(2)->count() }}</strong> besin
                    </span>
                    <span class="text-gray-600 dark:text-gray-400">
                        📅 <strong class="text-green-600">{{ count($days) }}</strong> gün
                    </span>
                    <span class="text-gray-600 dark:text-gray-400">
                        🍽️ <strong class="text-orange-600">{{ count($times) }}</strong> öğün
                    </span>
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    Son güncelleme: <strong>Şimdi</strong>
                </div>
            </div>
        </div>
    </x-filament-tables::container>

    {{-- Geliştirilmiş Yemek Ekleme Modalı --}}
        <x-filament::modal
            slide-over="right"
            id="addMealModal"
            width="5xl"
        >
            <x-slot name="header">
                Besin Ekle <br>
                {{$selectedDayLabel . ' • ' . $selectedTimeLabel}}
            </x-slot>
            {{-- Modal Body --}}
            <div class="space-y-4">
                <div class="flex items-center gap-3 mb-6">
                    <h4 class="text-lg font-bold">Kategoriler</h4>
                </div>

                @foreach($this->getMealsByCategory() as $index => $category)
                    <div
                        x-data="{ open: false }"
                        class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden"
                    >
                        {{-- Accordion Başlık --}}
                        <button
                            type="button"
                            @click="open = !open"
                            class="w-full flex items-center justify-between px-5 py-3 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                        >
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 font-bold text-sm">
                                    {{ mb_substr($category->name, 0, 1) }}
                                </div>
                                <span class="font-medium text-gray-800 dark:text-gray-200">
                        {{ $category->name }}
                    </span>
                            </div>
                            <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $category->meals->count() }} besin
                    </span>
                                <svg
                                    :class="{'rotate-180': open}"
                                    class="w-4 h-4 text-gray-500 transition-transform duration-200"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </button>

                        {{-- Accordion İçerik --}}
                        <div
                            x-show="open"
                            x-transition
                            class="px-5 py-3 ps-2 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700"
                        >
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                                @foreach($category->meals as $meal)
                                    <button
                                        wire:click="quickAddMeal({{ $meal->id }})"
                                        class="flex items-center justify-between w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-primary-50 hover:border-primary-400 dark:hover:bg-primary-900/20 transition-colors duration-200 shadow-sm"
                                    >
                            <span class="text-sm font-medium text-gray-350 dark:text-gray-300">
                                {{ $meal->name }}
                            </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $meal->default_quantity }} {{ $meal->unit->label() }}
                            </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- Modal Footer --}}
            <x-slot name="footer">
                <x-filament::button
                    color="danger"
                    wire:click="closeAddMealModal"
                >
                    Kapat
                </x-filament::button>
            </x-slot>
        </x-filament::modal>
    {{-- JavaScript kodları --}}
    @script
    <script>
        // URL açma
        $wire.on('openUrl', (data) => {
            window.open(data[0].url, '_blank');
        });

        // Panoya kopyalama
        $wire.on('copyToClipboard', (data) => {
            navigator.clipboard.writeText(data[0].text).then(() => {
                console.log('Link kopyalandı:', data[0].text);
            });
        });
    </script>
    @endscript
</x-filament-panels::page>
