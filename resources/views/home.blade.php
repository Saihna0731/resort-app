@php
    /**
     * Data comes from ResortController@index:
     * - $resorts (Collection<App\Models\Resort>)
     * - $regions (Collection<string>)
     * - $selectedRegion (string)
     * - $searchQuery (string)
     */
    $promoSlides = [
        [
            'title' => 'Зуны онцгой урамшуулал',
            'subtitle' => 'Хамгийн сайхан амралтуудыг 30% хаямдралтай үнээр аваарай!',
            'image' => 'https://images.unsplash.com/photo-1738255304177-8171219411e2?w=1200',
        ],
        [
            'title' => 'Шинэ жилийн амралт',
            'subtitle' => '3 хоног захиалаад 1 хоног үнэгүй!',
            'image' => 'https://images.unsplash.com/photo-1695554477492-303aacd40561?w=1200',
        ],
        [
            'title' => 'Гэр бүлийн багц',
            'subtitle' => 'Гэр бүлд тусгай үнэ',
            'image' => 'https://images.unsplash.com/photo-1695554361609-4f0538213a39?w=1200',
        ],
    ];

    $topResorts = ($resorts ?? collect())->take(6);
    $nearbyResorts = ($resorts ?? collect())->skip(6)->take(6);
@endphp

<!DOCTYPE html>
<html lang="mn" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Монгол Аялал - Амралтын газар захиалга</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        /* Custom scrollbar hide */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Line clamp */
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    
    <!-- Language & Dark Mode Toggle -->
    <div class="fixed top-4 right-4 z-50 flex gap-2">
        <button onclick="toggleDarkMode()" class="bg-gray-800 dark:bg-gray-600 text-white rounded-full p-2 shadow-lg hover:bg-gray-700 transition-colors" aria-label="Toggle dark mode">
            <i data-lucide="moon" class="w-4 h-4"></i>
        </button>
        <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-full px-3 py-1.5 shadow-lg">
            <button onclick="toggleLanguage()" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                MN
            </button>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-md mx-auto bg-white dark:bg-gray-900 min-h-screen shadow-xl pb-24">
        
        <!-- Hero Section -->
        <div class="relative h-96">
            <img src="https://images.unsplash.com/photo-1571315742781-a6140d3a8bd5?w=1200" 
                 alt="Mongolia landscape" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black/60"></div>
            <div class="absolute inset-0 flex flex-col justify-end p-6">
                <h1 class="text-white text-3xl font-medium mb-2">Монгол орноо нээцгээе</h1>
                <p class="text-white/90 text-lg mb-6">Төгс амралт таныг хүлээж байна</p>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="px-4 -mt-16 relative z-10 mb-8">
            <form action="{{ route('home') }}" method="GET" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 space-y-3">
                <div class="flex items-center gap-3 pb-3 border-b border-gray-100 dark:border-gray-700">
                    <i data-lucide="map-pin" class="w-5 h-5 text-gray-400"></i>
                    <input type="text" 
                           name="q" 
                           placeholder="Хаашаа явахыг хүсч байна вэ?" 
                           value="{{ $searchQuery ?? '' }}"
                           class="flex-1 outline-none text-gray-900 dark:text-white dark:bg-gray-800 placeholder:text-gray-400">
                </div>
                <div class="flex items-center gap-3 pb-3 border-b border-gray-100 dark:border-gray-700">
                    <i data-lucide="calendar" class="w-5 h-5 text-gray-400"></i>
                    <input type="text" 
                           name="dates" 
                           placeholder="Хугацаа сонгох" 
                           class="flex-1 outline-none text-gray-900 dark:text-white dark:bg-gray-800 placeholder:text-gray-400">
                </div>
                <div class="flex items-center gap-3">
                    <i data-lucide="users" class="w-5 h-5 text-gray-400"></i>
                    <input type="number" 
                           name="guests" 
                           placeholder="Зочдын тоо" 
                           class="flex-1 outline-none text-gray-900 dark:text-white dark:bg-gray-800 placeholder:text-gray-400">
                </div>
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-green-500 to-blue-500 text-white rounded-xl font-semibold hover:from-green-600 hover:to-blue-600 transition-all">
                    Хайх
                </button>
            </form>
        </div>

        <!-- Promotional Slider -->
        <div class="px-4 mb-8">
            <h2 class="text-xl font-medium mb-4 dark:text-white">Онцгой санал</h2>
            <div class="relative" id="promoSlider">
                @foreach ($promoSlides as $index => $slide)
                <div class="promo-slide {{ $index === 0 ? '' : 'hidden' }}" data-index="{{ $index }}">
                    <div class="relative h-48 rounded-2xl overflow-hidden">
                        <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-transparent flex flex-col justify-center px-6">
                            <h3 class="text-white text-lg font-medium mb-1">{{ $slide['title'] }}</h3>
                            <p class="text-white/90">{{ $slide['subtitle'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
                
                <!-- Slider Controls -->
                <button onclick="prevSlide()" class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white transition-colors">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </button>
                <button onclick="nextSlide()" class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white transition-colors">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </button>
                
                <!-- Dots -->
                <div class="flex justify-center gap-2 mt-3">
                    @foreach ($promoSlides as $index => $slide)
                        <div class="promo-dot h-1.5 rounded-full transition-all {{ $index === 0 ? 'w-6 bg-blue-500' : 'w-1.5 bg-gray-300' }}" data-index="{{ $index }}"></div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Top Resorts -->
        <div class="mb-8">
            <div class="px-4 mb-4 flex items-center justify-between">
                <h2 class="text-xl font-medium dark:text-white">Шилдэг амралтын газрууд</h2>
                <a href="{{ route('home') }}" class="text-blue-500 hover:text-blue-600">Бүгдийг үзэх</a>
            </div>
            <div class="flex gap-4 overflow-x-auto px-4 scrollbar-hide">
                @foreach ($topResorts as $resort)
                <div class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow flex-shrink-0 w-72 cursor-pointer">
                    <div class="relative h-48">
                        <img src="https://images.unsplash.com/photo-1501117716987-c8e1ecb210ff?q=80&w=1200&auto=format&fit=crop" alt="{{ $resort->name }}" class="w-full h-full object-cover">
                        <button type="button" onclick="event.stopPropagation(); toggleFavorite({{ (int) $resort->id }});" 
                                class="absolute top-3 right-3 w-9 h-9 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white transition-colors">
                            <i data-lucide="heart" class="w-5 h-5 text-gray-700 dark:text-gray-300" data-fav-icon="{{ (int) $resort->id }}"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $resort->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                            {{ $resort->region ?? '-' }} • {{ $resort->location ?? '-' }}
                        </p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-bold text-lg text-gray-900 dark:text-white">₮{{ number_format((int) ($resort->price_per_night ?? 0)) }}</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400"> /шөнө</span>
                            </div>
                            <button type="button"
                                    class="px-4 py-2 bg-gradient-to-r from-green-500 to-blue-500 text-white rounded-full hover:from-green-600 hover:to-blue-600 transition-all">
                                Захиалах
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Nearby Resorts -->
        <div class="mb-8">
            <div class="px-4 mb-4 flex items-center justify-between">
                <h2 class="text-xl font-medium dark:text-white">Ойролцоох амралтын газрууд</h2>
                <a href="{{ route('home') }}" class="text-blue-500 hover:text-blue-600">Бүгдийг үзэх</a>
            </div>
            <div class="flex gap-4 overflow-x-auto px-4 scrollbar-hide">
                @foreach ($nearbyResorts as $resort)
                <div class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow flex-shrink-0 w-72 cursor-pointer">
                    <div class="relative h-48">
                        <img src="https://images.unsplash.com/photo-1501117716987-c8e1ecb210ff?q=80&w=1200&auto=format&fit=crop" alt="{{ $resort->name }}" class="w-full h-full object-cover">
                        <button type="button" onclick="event.stopPropagation(); toggleFavorite({{ (int) $resort->id }});" 
                                class="absolute top-3 right-3 w-9 h-9 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white transition-colors">
                            <i data-lucide="heart" class="w-5 h-5 text-gray-700 dark:text-gray-300" data-fav-icon="{{ (int) $resort->id }}"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $resort->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                            {{ $resort->region ?? '-' }} • {{ $resort->location ?? '-' }}
                        </p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-bold text-lg text-gray-900 dark:text-white">₮{{ number_format((int) ($resort->price_per_night ?? 0)) }}</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400"> /шөнө</span>
                            </div>
                            <button type="button"
                                    class="px-4 py-2 bg-gradient-to-r from-green-500 to-blue-500 text-white rounded-full hover:from-green-600 hover:to-blue-600 transition-all">
                                Захиалах
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Popular by Region -->
        <div class="px-4 mb-8">
            <h2 class="text-xl font-medium mb-4 dark:text-white">Бүс нутгаар</h2>
            <div class="flex gap-2 overflow-x-auto mb-4 scrollbar-hide">
                <a href="{{ route('home') }}" class="region-btn px-4 py-2 rounded-full whitespace-nowrap transition-all {{ ($selectedRegion ?? 'ALL') === 'ALL' ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">Бүгд</a>
                @foreach(($regions ?? collect()) as $region)
                    <a href="{{ route('home', ['region' => $region, 'q' => $searchQuery ?? null]) }}" class="region-btn px-4 py-2 rounded-full whitespace-nowrap transition-all {{ ($selectedRegion ?? 'ALL') === $region ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">{{ $region }}</a>
                @endforeach
            </div>
            <div class="grid grid-cols-1 gap-4">
                @foreach($topResorts->take(2) as $resort)
                <div class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="flex gap-3">
                        <div class="w-32 h-32 flex-shrink-0">
                            <img src="https://images.unsplash.com/photo-1501117716987-c8e1ecb210ff?q=80&w=900&auto=format&fit=crop" alt="{{ $resort->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 p-3">
                            <h3 class="font-semibold mb-1 dark:text-white">{{ $resort->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">{{ $resort->region ?? '-' }} • {{ $resort->location ?? '-' }}</p>
                            <div class="flex items-center gap-1 mb-2">
                                <span class="font-bold dark:text-white">₮{{ number_format((int) ($resort->price_per_night ?? 0)) }}</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">/шөнө</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- Bottom Navigation -->
    <div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 px-4 py-3 z-50">
        <div class="flex items-center justify-around max-w-md mx-auto">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 flex-1 transition-colors">
                <i data-lucide="home" class="w-6 h-6 text-blue-500"></i>
                <span class="text-xs text-blue-500 font-medium">Нүүр</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 flex-1 transition-colors">
                <i data-lucide="compass" class="w-6 h-6 text-gray-500 dark:text-gray-400"></i>
                <span class="text-xs text-gray-500 dark:text-gray-400">Судлах</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 flex-1 transition-colors">
                <i data-lucide="calendar" class="w-6 h-6 text-gray-500 dark:text-gray-400"></i>
                <span class="text-xs text-gray-500 dark:text-gray-400">Захиалга</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 flex-1 transition-colors">
                <i data-lucide="heart" class="w-6 h-6 text-gray-500 dark:text-gray-400"></i>
                <span class="text-xs text-gray-500 dark:text-gray-400">Дуртай</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 flex-1 transition-colors">
                <i data-lucide="user" class="w-6 h-6 text-gray-500 dark:text-gray-400"></i>
                <span class="text-xs text-gray-500 dark:text-gray-400">Профайл</span>
            </a>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Dark Mode Toggle
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
        }

        // Load dark mode preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }

        // Language Toggle
        function toggleLanguage() {
            // Энд хэлний солих функц нэмнэ
            alert('Хэл солих функц удахгүй нэмэгдэнэ');
        }

        // Promo Slider
        let currentSlide = 0;
        const slides = document.querySelectorAll('.promo-slide');
        const dots = document.querySelectorAll('.promo-dot');

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('hidden', i !== index);
            });
            dots.forEach((dot, i) => {
                if (i === index) {
                    dot.classList.remove('w-1.5', 'bg-gray-300');
                    dot.classList.add('w-6', 'bg-blue-500');
                } else {
                    dot.classList.remove('w-6', 'bg-blue-500');
                    dot.classList.add('w-1.5', 'bg-gray-300');
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        }

        // Auto-play slider
        setInterval(nextSlide, 5000);

        // Favorites (localStorage)
        function getFavorites() {
            try {
                return JSON.parse(localStorage.getItem('favorites') || '[]');
            } catch (e) {
                return [];
            }
        }

        function setFavorites(favs) {
            localStorage.setItem('favorites', JSON.stringify(favs));
        }

        function renderFavoriteIcons() {
            const favs = new Set(getFavorites().map(Number));
            document.querySelectorAll('[data-fav-icon]').forEach((el) => {
                const id = Number(el.getAttribute('data-fav-icon'));
                if (favs.has(id)) {
                    el.classList.add('fill-red-500', 'text-red-500');
                    el.classList.remove('text-gray-700', 'dark:text-gray-300');
                } else {
                    el.classList.remove('fill-red-500', 'text-red-500');
                    el.classList.add('text-gray-700', 'dark:text-gray-300');
                }
            });
        }

        function toggleFavorite(resortId) {
            const favs = new Set(getFavorites().map(Number));
            const id = Number(resortId);
            if (favs.has(id)) favs.delete(id); else favs.add(id);
            setFavorites(Array.from(favs));
            renderFavoriteIcons();
        }

        renderFavoriteIcons();
    </script>
</body>
</html>
